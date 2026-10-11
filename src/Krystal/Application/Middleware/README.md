Middleware
==========

Middleware lets you intercept incoming requests before they reach a controller
and clean up after the response has been sent. It exists so that cross-cutting
concerns — authentication, CSRF, rate limiting, logging, HTTPS enforcement,
shared template data — live in one place instead of being duplicated across
every controller action.


Typical uses:

- Authentication / authorization checks
- CSRF verification for state-changing requests
- Rate limiting and throttling
- Request logging, timing, and profiling
- Forcing HTTPS, trailing-slash normalization, locale detection
- Injecting shared template variables (menus, breadcrumbs, feature flags)
- CORS headers and preflight handling

---

## The Contract

Every middleware must implement `Krystal\Application\Middleware\MiddlewareInterface`:

```php
<?php

namespace Krystal\Application\Middleware;

use Krystal\InstanceManager\ServiceLocatorInterface;
use Krystal\Application\Route\RouteMatchInterface;

interface MiddlewareInterface
{
    /**
     * Runs before the controller action.
     *
     * Return true to continue the pipeline. Return false to halt
     * immediately — the controller action is not invoked and the
     * response is sent as-is. On halt, the middleware is responsible
     * for setting the response status code and body.
     *
     * @param \Krystal\InstanceManager\ServiceLocatorInterface $sl
     * @param RouteMatchInterface $route
     * @return bool True to continue, false to halt
     */
    public function process(ServiceLocatorInterface $sl, RouteMatchInterface $route): bool;

    /**
     * Runs after the action has finished (or the pipeline halted).
     *
     * Intended for cleanup, logging, and other side effects.
     * Runs in reverse order of process().
     *
     * @param \Krystal\Application\Route\RouteMatchInterface $sl
     * @param RouteMatchInterface $route
     * @return void
     */
    public function terminate(ServiceLocatorInterface $sl, RouteMatchInterface $route);
}
```

Middleware is not required to extend any base class. Constructors must take no arguments — every dependency is retrieved from the service locator inside `process()` / `terminate()`.

---

## Declaring Middleware

Middleware can be declared in two places. Both are merged at dispatch time.

### 1. On a controller (applies to every action)

```php
<?php

namespace Cms\Controller;

use Krystal\Application\Controller\AbstractController;

final class Dashboard extends AbstractController
{
    protected $middlewares = [
        \Cms\Middleware\AuthMiddleware::class,
        \Cms\Middleware\AuditLogMiddleware::class,
    ];

    public function indexAction()
    {
        // ...
    }
}
```

### 2. On a route (applies only to that route)

```php
<?php

// Module::getRoutes()

return [
    '/admin/users' => [
        'controller' => 'Admin@usersAction',
        'middleware' => [
            \Cms\Middleware\AdminOnlyMiddleware::class,
        ],
    ],
];
```

### Merge order

For a given request, the final queue is:

```text
[ controller middlewares..., route middlewares... ]
```

Controller middleware runs first, then route middleware. This lets you apply broad concerns (e.g. session setup) at the controller level and narrow concerns (e.g. admin-only) at the route level.

---

## Execution Order

Given this declaration:

```php
// Controller
protected $middlewares = [Auth::class, AuditLog::class];

// Route
'middleware' => [AdminOnly::class],
```

The controller is **instantiated** first (that is how its `$middlewares` are read), but it is not initialized. Then the `process()` calls run in this order:

```text
Controller instantiated (constructor only)
Auth.process()
  AuditLog.process()
    AdminOnly.process()
      → Controller::initialize()   (haltOnDemand, bootstrap, auth hooks, translations)
        → Controller::action()
```

And `terminate()` runs in reverse:

```text
AdminOnly.terminate()
  AuditLog.terminate()
    Auth.terminate()
```

`terminate()` is guaranteed to run via a `finally` block, including when:

- The controller throws an exception (the kernel then sends an empty response)
- A middleware halts the pipeline (only the middleware whose `process()` was already invoked receives `terminate()`, including the one that halted)
- The controller halts during `initialize()` (`haltOnDemand()`, auth hooks)
- The action returns `false` (404)

> **Timing note:** `terminate()` runs once the action has finished. On a normal (200) or 404 response it is invoked *before* the final `$response->send()` call, so do not rely on the response having been flushed to the client. Keep `terminate()` work light.

---

## Halting the Pipeline

Returning `false` from `process()` stops everything. The controller is never initialized, its action is never invoked, and no further middleware runs.

On halt, the kernel calls `$response->send('')`. Whatever status code and headers you set on the response are sent, with an empty body. If you want a body, call `$response->send('...')` yourself before returning `false`.

```php
<?php

namespace Cms\Middleware;

use Krystal\Application\Middleware\MiddlewareInterface;
use Krystal\InstanceManager\ServiceLocatorInterface;
use Krystal\Application\Route\RouteMatchInterface;

final class AuthMiddleware implements MiddlewareInterface
{
    public function process(ServiceLocatorInterface $sl, RouteMatchInterface $route): bool
    {
        $auth = $sl->get('authManager');

        if (!$auth->isLoggedIn()) {
            $response = $sl->get('response');
            $response->setStatusCode(302);
            $response->getHeaderBag()->appendPair('Location', '/login');
            return false; // halt
        }

        return true; // continue
    }

    public function terminate(ServiceLocatorInterface $sl, RouteMatchInterface $route)
    {
        // no-op
    }
}
```

> ⚠️ **Important:** when you halt, set the status code and headers (and body, if needed) yourself. If you set nothing, the client receives an empty `200` response.

> ⚠️ **Avoid `$response->redirect()` inside middleware.** It calls `exit()`, so no `terminate()` will run for any middleware.

> **Status codes:** `setStatusCode()` only knows the codes in `StatusGenerator`. An unknown code (for example `419`) is silently ignored and no status line is sent.

---

## Duplicate Detection

The same middleware class must not appear twice in the merged queue, whether that's twice in the controller, twice in the route, or once in each. `MiddlewareRunner::setQueue()` performs this check and throws a `LogicException` on violation:

```php
// This throws:
protected $middlewares = [Auth::class, Auth::class];

// This also throws:
protected $middlewares = [Auth::class];
// route:
'middleware' => [Auth::class],
```

The check runs once per request, before any middleware is instantiated.

---

## Lifecycle Guarantees

| Guarantee | Notes |
|---|---|
| Middleware runs before `controller->initialize()` | `bootstrap()`, auth hooks, and translation loading are all deferred (the controller is constructed earlier, but not initialized) |
| Middleware does not run when no route matches | The not-found handler is invoked directly and bypasses the queue |
| Middleware **does** run when an action returns `false` | The 404 is rendered afterwards, so `process()` and `terminate()` have already run |
| Middleware does not run on internal forwards | `forward()` calls the target action directly |
| Each middleware class is instantiated once per request | Cached by class name inside `MiddlewareRunner` |
| `terminate()` runs in reverse order of `process()` | Matches PSR-15 semantics |
| `terminate()` receives only middleware whose `process()` was invoked | A halting middleware still gets `terminate()` |
| `terminate()` exceptions are swallowed | One failing middleware cannot block the rest |
| `terminate()` is called from a `finally` block | Runs even if the controller throws |

---

## Accessing Services

Middleware receives the framework's service locator. Every service available in controllers is available here by the same name:

```php
$auth     = $sl->get('authManager');
$session  = $sl->get('sessionBag');
$request  = $sl->get('request');
$response = $sl->get('response');
$view     = $sl->get('view');
$db       = $sl->get('db');
$config   = $sl->get('config');
$logger   = $sl->get('logger');
// etc.
```

> Optional components (`logger`, `cache`, `captcha`, `config`, …) are registered as `false` when they are not configured. Check the value before using it.

The `RouteMatchInterface` argument gives you the matched route's details:

```php
$route->getMatchedURI();          // e.g. '/admin/users/42'
$route->getMatchedURITemplate();  // e.g. '/admin/users/(:var)'
$route->getVariables();           // ['42']
$route->getMiddleware();          // declared route-level middleware
$route->getMethod();              // currently always null (the router does not populate it)
```

To check the HTTP method, use `$sl->get('request')->getMethod()`.

---

## Common Patterns

### Injecting shared view variables

```php
final class MenuMiddleware implements MiddlewareInterface
{
    public function process(ServiceLocatorInterface $sl, RouteMatchInterface $route): bool
    {
        $menu = $sl->get('moduleManager')
                   ->getModule('Cms')
                   ->getService('menuManager')
                   ->getTree();

        $sl->get('view')->addVariable('mainMenu', $menu);
        return true;
    }

    public function terminate(ServiceLocatorInterface $sl, RouteMatchInterface $route) {}
}
```

### CSRF verification on POST

```php
final class CsrfMiddleware implements MiddlewareInterface
{
    public function process(ServiceLocatorInterface $sl, RouteMatchInterface $route): bool
    {
        $request = $sl->get('request');

        if ($request->getMethod() !== 'POST') {
            return true;
        }

        $csrf = $sl->get('csrfProtector');

        if (!$csrf->isValid($request->getPost('_token'))) {
            $response = $sl->get('response');
            $response->setStatusCode(403);
            $response->send('CSRF token mismatch');
            return false;
        }

        return true;
    }

    public function terminate(ServiceLocatorInterface $sl, RouteMatchInterface $route) {}
}
```

### Request timing in `terminate()`

```php
final class TimingMiddleware implements MiddlewareInterface
{
    private $start;

    public function process(ServiceLocatorInterface $sl, RouteMatchInterface $route): bool
    {
        $this->start = microtime(true);
        return true;
    }

    public function terminate(ServiceLocatorInterface $sl, RouteMatchInterface $route)
    {
        $logger = $sl->get('logger');

        if ($logger) {
            $elapsed = microtime(true) - $this->start;
            $logger->info(sprintf('%s took %.2f ms', $route->getMatchedURI(), $elapsed * 1000));
        }
    }
}
```

### HTTPS enforcement

```php
final class HttpsMiddleware implements MiddlewareInterface
{
    public function process(ServiceLocatorInterface $sl, RouteMatchInterface $route): bool
    {
        $request = $sl->get('request');

        if ($request->isSecure()) {
            return true;
        }

        $response = $sl->get('response');
        $response->setStatusCode(301);
        $response->getHeaderBag()->appendPair(
            'Location',
            'https://' . $request->getHost() . $request->getURI()
        );

        return false;
    }

    public function terminate(ServiceLocatorInterface $sl, RouteMatchInterface $route) {}
}
```

> For a site-wide redirect, the router's `ssl` option is simpler. Use middleware when you need it only for certain controllers or routes.

---

## Testing Middleware

Because middleware takes no constructor arguments and receives the service locator, it is straightforward to test in isolation.

`HttpResponse` and `HeaderBag` are `final` classes and cannot be mocked, so mock their interfaces instead:

```php
use Krystal\Http\HeaderBagInterface;
use Krystal\Http\Response\HttpResponseInterface;

final class AuthMiddlewareTest extends TestCase
{
    public function testHaltsWhenNotLoggedIn()
    {
        $auth = $this->createMock(AuthManager::class);
        $auth->method('isLoggedIn')->willReturn(false);

        $headerBag = $this->createMock(HeaderBagInterface::class);
        $headerBag->expects($this->once())
                  ->method('appendPair')
                  ->with('Location', '/login');

        $response = $this->createMock(HttpResponseInterface::class);
        $response->method('getHeaderBag')->willReturn($headerBag);
        $response->expects($this->once())->method('setStatusCode')->with(302);

        $sl = new ServiceLocator([
            'authManager' => $auth,
            'response'    => $response,
        ]);

        $route = new RouteMatch();
        $route->setMatchedURI('/admin');

        $middleware = new AuthMiddleware();
        $this->assertFalse($middleware->process($sl, $route));
    }
}
```

---

## Anti-Patterns

| Don't | Why |
|---|---|
| Return `false` from `process()` without setting a status code or headers | The client receives an empty `200` response |
| Call `$response->redirect()` inside middleware | It calls `exit()`, so `terminate()` never runs; set a `Location` header and return `false` instead |
| Perform heavy work inside `terminate()` | It may run before the response is flushed, so it can delay the client; move real work elsewhere |
| Throw exceptions from `terminate()` | They are swallowed by design; use a logger instead |
| Use `setStatusCode()` with codes missing from `StatusGenerator` (e.g. 419) | The status is silently ignored |
| Use an optional service without checking it | Unconfigured components (`logger`, `cache`, …) are `false` |
| Duplicate the same middleware class across controller and route | Throws `LogicException` |
| Instantiate services with `new` inside middleware | Bypasses the DI container; use `$sl->get(...)` |
| Assume middleware runs for forwarded actions | It does not; `forward()` is an internal call |
| Rely on `$route->getMethod()` | It is not populated; use `$sl->get('request')->getMethod()` |
| Rely on middleware ordering across declarations | Order is controller-first, route-second, and that is the only guarantee |

---

## Summary

- Declare middleware on a controller's `$middlewares` property and/or a route's `middleware` key.
- Implement `MiddlewareInterface` with a no-argument constructor.
- Return `true` to continue, `false` to halt, and set the status code, headers, and body when halting.
- Use `process()` for pre-action logic and `terminate()` for light post-action side effects.
- Trust the lifecycle: duplicates are rejected, `terminate()` is guaranteed for every middleware whose `process()` ran, and every middleware sees the same service instances as controllers.
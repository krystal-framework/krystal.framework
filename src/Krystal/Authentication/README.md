Authentication
===
The Authentication component provides a simple, secure, and flexible service to manage user authentication in your application. It handles session management, secure **Remember Me** cookies, and Role-Based Access Control (RBAC) out of the box.

To activate authentication features in your application, controllers should inherit from `\Krystal\Application\Controller\AbstractAuthAwareController` instead of the default `\Krystal\Application\Controller\AbstractController`. You can access the authentication manager directly in your controller actions via `$this->authManager`.

## Controller lifecycle hooks
When inheriting from `AbstractAuthAwareController`, implement the following protected methods to customize the authentication lifecycle:

-   `getAuthService()` — Must return the authentication service instance.
-   `onSuccess()` — Invoked automatically once a user successfully provides valid credentials.
-   `onFailure()` — Invoked automatically when an unauthenticated user attempts to access a protected controller action.
-   `onNoRights()` — Invoked automatically when an authenticated user attempts to access an area without the required permissions.

## The user provider

The framework needs to know how to fetch a user from your database. You provide this logic as a simple callable (a closure, a static method, or an invokable class) and register it with the AuthManager during your application's bootstrap phase.

The callable receives an identifier (either a string `$login` during login, or an int|string `$userId` during "Remember Me" validation) and must return an array with at least id, login, and `password_hash`. It can optionally include role and `remember_token_version`.

    $userProvider = function ($identifier) use ($pdo) {
        // Determine if searching by ID (Remember Me) or Login (Standard Login)
        $column = is_numeric($identifier) ? 'id' : 'username';
        
        $stmt = $pdo->prepare("SELECT id, username AS login, password_hash, role, remember_token_version FROM users WHERE {$column} = ?");
        $stmt->execute([$identifier]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $user ?: null;
    };
    
    // Register it with the framework's auth manager
    $this->authManager->setUserProvider($userProvider);

## Basic usage in controllers

### Logging in

To authenticate a user, call `login()` with the submitted credentials and an optional **Remember Me** flag:

    public function loginAction()
    {
        $login = $this->request->getPost('username');
        $plainPassword = $this->request->getPost('password');
        $rememberMe = (bool) $this->request->getPost('remember_me');
    
        if ($this->authManager->login($login, $plainPassword, $rememberMe)) {
            // Success! User is logged in.
            return $this->response->redirect('/dashboard');
        } else {
            // Failure. Show invalid credentials error.
            return $this->view->render('auth/login', ['error' => 'Invalid credentials']);
        }
    }

Note: If a password hash requires re-hashing due to algorithm updates, the framework detects this via `password_needs_rehash()`.

### Checking authentication status

    public function dashboardAction()
    {
        if ($this->authManager->isLoggedIn()) {
            $userId = $this->authManager->getId();
            $userRole = $this->authManager->getRole();
            $userLogin = $this->authManager->getLogin();
            
            // Retrieve full user payload
            $fullUser = $this->authManager->getUser();
            
            return $this->render('dashboard', compact('fullUser'));
        }
        
        return $this->redirect('/login');
    }

### Logging out

To sign a user out and clear their session, call logout():

    public function logoutAction()
    {
        $this->authManager->logout();
        return $this->response->redirect('/login');
    }

## Role-Based Access Control (RBAC)
Restrict access to specific controller actions or routes based on user roles.

### Using AuthManager::isAllowed()
Check if the current user possesses one of the allowed roles:

    public function deletePostAction()
    {
        // Allow only 'admin' or 'moderator'
        if (!$this->authManager->isAllowed(['admin', 'moderator'])) {
            return $this->view->render('errors/403');
        }
    
        // Proceed with action
    }

### Route protection

Attach permission definitions directly within route configurations using `allow` or `disallow` directives:

    // Grant access exclusively to specific roles
    '/admin/delete-post' => [
        'controller' => 'Admin\PostController@delete',
        'allow' => ['admin'],
    ],
    
    // Deny access to specific roles (triggers onNoRights automatically)
    '/admin/some-secured-action' => [
        'controller' => 'SomeController@someAction',
        'disallow' => ['reviewer'],
    ]

## Advanced: invalidate all sessions
Increment the `remember_token_version` column in your database to invalidate all active "Remember Me" tokens across every device without altering the user's password:

    public function changePasswordAction()
    {
        $userId = $this->authManager->getId();
        
        // 1. Update password in database...
        
        // 2. Invalidate all existing sessions/tokens across devices
        $stmt = $this->pdo->prepare("UPDATE users SET remember_token_version = remember_token_version + 1 WHERE id = ?");
        $stmt->execute([$userId]);
        
        return $this->redirect('/dashboard');
    }

## Built-in security
-   **Session Fixation Prevention** — Session IDs automatically regenerate on successful login.
-   **Timing Attack Mitigation** — Employs `hash_equals()` and strict comparisons (`===`) across hash checks.
-   **Native Hashing** — Enforces standard `password_hash()` and `password_verify()`.
-   **Stateless Token Verification** — Cookies contain HMAC-SHA256 signed payloads that auto-expire when `remember_token_version` changes.
-   **Lightweight Sessions** — Restricts session data storage to essential keys (`id`, `login`, `role`).
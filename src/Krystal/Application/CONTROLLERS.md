Controllers
===========

A **controller** is a simple class responsible for handling HTTP requests and returning responses. Controllers should stay **slim**: they only coordinate — they call services, prepare data, and render views. 

Business logic, data access, and complex processing belong in the model layer.

## Controller requirements

1. Must extend `\Krystal\Controller\AbstractController` 
2. Must be placed in a `Controller/` subdirectory inside a module 
3.  Action methods must be **public** and return a **string** (response content)


**Typical folder structure**

```
module
  - Site
    - Controller
     - Main.php
```

## Minimal Controller example

As a best practice, controllers must be slim. That means, they only have to know how to call methods and pass resulting values to view. They should never process data, and contain complicated logic.

    <?php
    
    namespace Site\Controller;
    
    use Krystal\Controller\AbstractController;
    
    class Main extends AbstractController
    {
        public function indexAction()
        {
            return 'Welcome to Krystal!';
        }
    }


## Routes

Routes are defined in the module's `getRoutes()` method (in `Module.php`).

    <?php
    
    namespace Site;
    
    use Krystal\Application\Module\AbstractModule;
    
    class Module extends AbstractModule
    {
        public function getRoutes()
        {
            return [
                '/' => [
                    'controller' => 'Main@indexAction'
                ],
                '/about' => [
                    'controller' => 'Main@aboutAction'
                ]
            ];
        }
    }

Format: `Module:Controller@Method`

### Default action (404 / Not Found)

Set in `config/app.php` under the router component:

    'router' => [
        'default' => 'Site:Main@notFoundAction',
    ],

When no route matches, `Main::notFoundAction()` will be called.

### Route parameters

Use `(:var)` to capture URI segments. They are passed as action method arguments in order.

Define

    '/user/(:var)' => [
        'controller' => 'User@profileAction'
    ],

Use

    public function profileAction($username)
    {
        // $username contains the value from /user/var
    }

**Multiple parameters:**

Define

    '/post/(:var)/comment/(:var)' => 'Post@showCommentAction'

Matches

    showCommentAction($postId, $commentId)

### Manually Trigger 404

Return `false` from an action to invoke the default (not found) handler:

    public function viewAction($id)
    {
        $post = $this->getModuleService('PostManager')->getById($id);
    
        if (!$post) {
            return false; // → triggers 404 (default action)
        }
    
        return $this->view->render('post/view', ['post' => $post]);
    }


### Controller shortcuts

These methods are inherited from `AbstractController`:

| Method                              | Description                                                                 | Example / Usage                                                                 |
|-------------------------------------|-----------------------------------------------------------------------------|---------------------------------------------------------------------------------|
| `redirectToRoute($route)`             | Performs HTTP redirect to the specified route                               | $this->redirectToRoute('Site:Main@dashboard');                                 |
| `forward($route, array $args = [])`   | Calls another controller's action internally and returns its response       | $sidebar = $this->forward('Site:Sidebar@latestNews', ['limit' => 5]);          |
| `getWithAssetPath($path, $module = null)` | Generates full public URL to an asset (CSS/JS/image) in current or specified module | $css = $this->getWithAssetPath('/css/style.css');                              |
| `getWithViewPath($path, $module = null, $theme = null)` | Returns filesystem path to a view file                             | $path = $this->getWithViewPath('partial/header.phtml', 'Site', 'default');     |


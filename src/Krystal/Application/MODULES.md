Modules
=======

A **module** is a self-contained mini-application. It typically includes:

- Controllers (to map routes to actions)
- Views (widgets and templates) 
- Data mappers (to work with databases)
- Services  (to handle business logic)
- Configuration
- Translations
- Assets (CSS, JS, images)

Modules promote clean separation of concerns and make large applications easier to maintain and extend.

## Creating a new module

1. Create a folder with the module name (use **UpperCamelCase** recommended) Example: `News`, `Gallery`, `Shop`

2. Inside the folder, create a file named `Module.php`

3. The file must follow **PSR-4**  autoloading and extend `AbstractModule`

**Example**

    <?php
    
    namespace News;
    
    use Krystal\Application\Module\AbstractModule;
    
    class Module extends AbstractModule
    {
        /**
         * Returns routes defined by this module
         */
        public function getRoutes()
        {
            return include __DIR__ . '/config/routes.php';
        }
    
        /**
         * Returns service providers for this module
         */
        public function getServiceProviders()
        {
            return [
                'postManager' => new stdclass
            ];
        }
    
        /**
         * Optional: module-specific configuration
         */
        public function getConfigData()
        {
            return [
                'version'     => '1.2.3',
                'author'      => 'Your Name',
                'description' => 'News & Blog module',
            ];
        }
    
        /**
         * Optional: module translations per language
         */
        public function getTranslations($lang)
        {
            return include __DIR__ . "/Translations/$lang.php";
        }
    }


## Inherited methods

These are inherited from AbstractModule and are mostly used inside controllers:

    $this->getService($name) // Get a service registered in this module
    $this->getServices() // Get all services of this module
    $this->hasService($name) // Check if a service exists
    $this->hasConfig($key = null) // Check if module config exists (or if a specific key exists)

## Methods
| Method                  | Required? | Returns                                      | Purpose                                      |
|-------------------------|-----------|----------------------------------------------|----------------------------------------------|
| `getRoutes()`             | Yes       | array                                        | Module routes (see Routing docs)             |
| `getServiceProviders()`   | Yes       | array (name => factory / class)              | Registers services for dependency injection  |
| `getConfigData()`         | Optional  | array                                        | Module metadata, settings, version info      |
| `getTranslations($lang)`  | Optional  | array                                        | Language-specific translation arrays         |

## Working with modules

### Get a service from current module

    public function indexAction()
    {
        $postManager = $this->getModuleService('postManager');
        $posts = $postManager->getLatest(10);
    }


### Get a service from any module

    public function someAction()
    {
        $userManager = $this->getService('Users', 'userManager');
        $user = $userManager->findById(42);
    }

### Get full module object

    $newsModule = $this->moduleManager->getModule('News');
    $postManager = $newsModule->getService('postManager');

### Get current module name

    $currentModule = $this->moduleName; // e.g. 'News'

## Summary

Inside any controller

    <?php
    
    // Current module service
    $this->getModuleService('postManager');
    
    // Specific module service
    $this->getService('Shop', 'cartManager');
    
    // Current module name
    $this->moduleName;
    
    // Full module instance (advanced)
    $this->moduleManager->getModule('Gallery');

Modules are the foundation for building modular, maintainable applications . Keep your module folders clean, follow naming conventions, and leverage services for business logic.



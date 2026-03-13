View
====

The **View** service handles template rendering, variable management, asset paths, layout support, translation, and reusable UI fragments.  

It is available in every controller as `$this->view`.

Inside view templates (.phtml files), it is directly accessible as `$this` (no need for `$this->view`).

**Template Location & Naming Convention**

All view templates **must** follow this structure:

**{project root}/module/{ModuleName}/View/Template/{theme}/{template-name}.phtml**

- `{ModuleName}` – the module the controller belongs to (e.g. `Site`, `Admin`, `Blog`)
- `{theme}` – active theme name (set in configuration, default: `default`)
- File extension: **always** `.phtml`

**Example**

File location:  
`/module/Site/View/Template/default/dashboard.phtml`

Controller usage:

    public function dashboardAction()
    {
        return $this->view->render('dashboard');
    }

The argument 'dashboard' automatically resolves to dashboard.phtml in the current module + active theme.


## Configuration

View settings live under the view key in configuration file:

    'components' => [
        'view' => [
            'theme'     => 'default',               // string – active theme folder name
            'obfuscate' => false,                   // bool – enable HTML compression/minification
            'plugins'   => [                        // reusable asset bundles
                'jquery' => [
                    'scripts' => [
                        '@Site/js/jquery-3.7.1.min.js',
                    ],
                ],
                'bootstrap' => [
                    'stylesheets' => [
                        '@Site/css/bootstrap.min.css',
                    ],
                    'scripts' => [
                        '@Site/js/bootstrap.bundle.min.js',
                    ],
                ],
            ],
        ],
    ],

Important notes about @Module/ syntax

The @ prefix is a shortcut that resolves to:

    @Site/ → /module/Site/Assets/

So @Site/js/app.js becomes /module/Site/Assets/js/app.js.


## Variables

### Assign single variable

    addVariable(string $name, mixed $value): self

Pass one named value to the current template context.

**Example**

    $this->view->addVariable('title', 'Dashboard')


### Assign multiple variables

    addVariables(array $vars): self

Pass an array of values to the template at once.

**Example**

    $this->view->addVariables([
       'version' => '1.0',
       'system' => 'windows'
    ]);

### Retrieve variable with fallback
    getVariable(string $name, $default = null): mixed

Get a previously assigned variable, returning a default if missing.

**Example**

    <?= $this->view->getVariable('title', 'Untitled') ?>

### Check if specific variable exists

    hasVariable(string $name): bool

Determine whether a variable has been assigned.

**Example**

    $this->view->hasVariable('user');

Whether view has at least one variable.

### Check if any variables are set

    $this->view->hasVariables(): bool

Verify if the view context contains at least one variable.


## Layout

### Set master layout template

    setLayout(string $name, ?string $module = null): self

Define which template will wrap the main content.

**Example**

    $this->view->setLayout('layout')

Sets the master template.

### Disable layout for current render

    disableLayout(): self

Render content without applying any master layout.

### Check if layout is active

    hasLayout(): bool

Determine whether a master layout is currently set.

## Rendering

### Render template

    render(string $template, array $vars = []): string

Render the requested template and wrap it in the layout (if set).

**Example**

    $this->view->render('home')

### Render any template without layout

    renderRaw(string $module, string $theme, string $template, array $vars = [])

Render any template from any module/theme independently (no layout applied).

Example

    $this->view->renderRaw('Mail', 'templates', 'welcome')

### Check if template file exists

    templateExists(string $template): bool

Verify whether a template file is present on disk.

**Example**

    $this->view->templateExists($template)


## Output & Translation

### Echo string (with optional translation)

    show(string $string, bool $translate = true): void

Immediately echo a string (translated if requested).

**Example**

    <?php $this->show('Error occurred') ?>

### Return translated string (supports placeholders)

    translate(...$args): string

Return translated text (supports sprintf-style placeholders).

**Example**

    <?= $this->translate('Hello %s!', $name) ?>


### Compress the output

    setCompress(bool $value): self
    
Control HTML minification/compression for the current response.



## Assets & URLs

### Path to file in theme folder


    asset(string $path, ?string $module = null, bool $absolute = false)

Build URL/path to file inside current theme folder.

**Example**

    <img src="<?= $this->asset('images/logo.png') ?>">

### Path to file in module’s Assets/ folder

    moduleAsset(string $path, ?string $module = null, bool $absolute = false)

Build URL/path to file inside module’s Assets/ directory.

**Example**

    <script src="<?= $this->moduleAsset('js/app.js') ?>">

### Generate route URL

    url(...$args)

Generates URL to given controllers action.

Example

    <?= $this->url('Site:Profile', ['id' => 15]) ?>

### Get current theme name

    getTheme(): string

Return the name of the active theme.


## Quick Tips & Best Practices

-   Always return the result of `render()` from controller actions
-   Set layout once (bootstrap or base controller), not per action
-   Use `@Module/` syntax in config — it makes refactoring easier
-   Prefer `loadPartial()` over inline includes for reusable blocks
-   Load plugins early (base controller or middleware) to avoid duplication
-   Use `renderRaw()` for emails, PDFs, HTML fragments
-   Keep partials small and logic-free (move logic to widgets or services)


## Plugin bag

Central manager for grouping and loading CSS/JavaScript files under named plugins to avoid path duplication.

**Access** 

    $this->view->getPluginBag()


**Typical pattern**

    // Controller
    $this->view->getPluginBag()->load('bootstrap');
    
    // Layout template
    <?php foreach ($this->getPluginBag()->getStylesheets() as $css): ?>
    <link rel="stylesheet" href="<?= $this->asset($css) ?>">
    <?php endforeach; ?>
    
    <?php foreach ($this->getPluginBag()->getScripts() as $js): ?>
    <script src="<?= $this->asset($js) ?>"></script>
    <?php endforeach; ?>

### Load plugin(s)

    load(string|array $plugins): self

Include one or more pre-defined plugins.

**Usage example**

    $this->view->getPluginBag()->load(['jquery', 'font-awesome']);


### Register plugin(s) at runtime

    register(array $collection): self

Define new plugin(s) dynamically (outside config).

**Usage example**

    $this->view->getPluginBag()->register([
        'datatables' => [
            'stylesheets' => ['@Admin/css/datatables.min.css'],
            'scripts'     => ['@Admin/js/datatables.min.js'],
        ]
    ]);

### Add stylesheet(s)

    appendStylesheet(string $url, bool $once = true): self 
    appendStylesheets(array $urls, bool $once = true): self

Manually append CSS file(s) to the collection.

### Add script(s)

    appendScript(string $url, bool $once = true): self 
    appendScripts(array $urls, bool $once = true): self

Manually append JavaScript file(s) to the collection.


### Add stylesheet(s) to load last

    appendLastStylesheet(string $url): self
    appendLastStylesheets(array $urls): self

Append CSS that should appear after others (overrides).

### Add script(s) to load last

    appendLastScript(string $url): self
    appendLastScripts(array $urls): self

Append JS that should execute last (initialization code).

### Get all stylesheets / scripts

    getStylesheets(): array 
    getScripts(): array

Retrieve collected asset paths (normal + last).


## Partial bag

Manages reusable template fragments (pagination, alerts, sidebars, etc.) that can be shared across multiple templates.

**Access**

    $this->view->getPartialBag()

**Setup example** (bootstrap)

    $this->view->getPartialBag()->addPartialDir(BASE_PATH . '/shared/partials');

**Rendering example**

    <?php $this->loadPartial('breadcrumbs') ?>
    <?php $this->loadPartials(['flash-messages', 'footer-links']) ?>


### Register shared partial directory

    addPartialDir(string $dir): 
    self addPartialDirs(array $dirs): self

Add folder(s) where framework will search for partial files.

**Usage example**

    $this->view->getPartialBag()->addPartialDir(BASE_PATH . '/shared/partials');

### Register static partial

    addStaticPartial(string $baseDir, string $name): self

Map exact file path to a partial name (useful for vendor/special files).

**Usage example**

    $this->view->getPartialBag()->addStaticPartial(
        BASE_PATH . '/vendor/campaign/views',
        'promo-banner'
    );

### Resolve partial file path

    getPartialFile(string $name): string

Get full filesystem path for a named partial (throws if not found).
**Note** — normally called internally by loadPartial().

### Load partial

    loadPartial(string $name, array $vars = []): void

Include and output a registered partial (with optional extra variables).

**Usage example**

    $this->loadPartial('user-card', ['user' => $profile, 'size' => 'lg']);

### Load multiple partials

    loadPartials(array $names): void

Render several partials in sequence.

**Example**

    $this->loadPartials(['header-alert', 'sidebar-quick', 'footer-copyright']);






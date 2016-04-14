<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\View;

use RuntimeException;
use Krystal\I18n\TranslatorInterface;
use Krystal\Application\View\Resolver\ResolverInterface;
use Krystal\Form\Navigation\Breadcrumbs\BreadcrumbBag;
use Krystal\Form\Compressor\HtmlCompressor;
use Krystal\Application\Route\UrlBuilderInterface;

final class ViewManager implements ViewManagerInterface
{
    /**
     * Message translator to be used in templates
     * 
     * @var \Krystal\I18n\TranslatorInterface
     */
    private $translator;

    /**
     * Template resolver
     * 
     * @var \Krystal\Application\View\Resolver\ResolverInterface
     */
    private $resolver;

    /** 
     * Whether to compress an output
     * 
     * @var boolean
     */
    private $compress;

    /**
     * URL builder to build paths in templates
     * 
     * @var \Krystal\Application\Route\UrlBuilderInterface
     */
    private $urlBuilder;

    /**
     * Plugin bag to manage plugins
     * 
     * @var \Krystal\Application\View\PluginBag
     */
    private $pluginBag;

    /**
     * Template variables
     * 
     * @var array
     */
    private $variables = array();

    /**
     * Optional template layout
     * 
     * @var string
     */
    private $layout;

    /**
     * General module name where shared layout file will taken
     * 
     * @var string
     */
    private $layoutModule;

    /**
     * A module name which belongs to layout
     * 
     * @var string
     */
    private $module;

    /**
     * Current theme name
     * 
     * @var string
     */
    private $theme;

    /**
     * Modules directory
     * 
     * @var string
     */
    private $moduleDir;

    const TEMPLATE_PARAM_MODULES_DIR = 'module';
    const TEMPLATE_PARAM_BASE_DIR = 'View/Template';
    const TEMPLATE_PARAM_ASSETS_DIR = 'Assets';
    const TEMPLATE_PARAM_EXTENSION = 'phtml';
    const TEMPLATE_PARAM_FRAGMENT_VAR_NAME = 'fragment';

    /**
     * State initialization
     * 
     * @param string $moduleDir
     * @param \Krystal\Application\View\PluginBagInterface $pluginBag
     * @param \Krystal\I18n\TranslatorInterface $translator
     * @param \Krystal\Application\Route\UrlBuilderInterface $urlBuilder
     * @param $compress Whether to compress an output
     * @return void
     */
    public function __construct($moduleDir, PluginBagInterface $pluginBag, TranslatorInterface $translator, UrlBuilderInterface $urlBuilder, $compress)
    {
        $this->moduleDir = $moduleDir;
        $this->pluginBag = $pluginBag;
        $this->translator = $translator;
        $this->urlBuilder = $urlBuilder;
        $this->setCompress($compress);
    }

    /**
     * Returns translator's instance
     * 
     * @return \Krystal\I18n\Translator
     */
    public function getTranslator()
    {
        return $this->translator;
    }

    /**
     * Returns plugin bag
     * 
     * @return \Krystal\Application\View\PluginBag
     */
    public function getPluginBag()
    {
        return $this->pluginBag;
    }

    /**
     * Returns prepared bread-crumb bag
     * 
     * @return \Krystal\Application\View\BreadcrumbBag
     */
    public function getBreadcrumbBag()
    {
        static $breadcrumbBag = null;

        if ($breadcrumbBag === null) {
            $breadcrumbBag = new BreadcrumbBag();
        }

        return $breadcrumbBag;
    }

    /**
     * Returns block bag
     * 
     * @return \Krystal\Application\View\BlockBag
     */
    public function getBlockBag()
    {
        static $blockBag = null;

        if ($blockBag === null) {
            $blockBag = new BlockBag();
        }

        return $blockBag;
    }

    /**
     * Defines target module
     * 
     * @param string $module
     * @return \Krystal\Application\View\ViewManager
     */
    public function setModule($module)
    {
        $this->module = $module;
        return $this;
    }

    /**
     * Returns target module
     * 
     * @return string
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * Defines target theme
     * 
     * @param string $theme
     * @return \Krystal\Application\View\ViewManager
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;
        return $this;
    }

    /**
     * Returns current theme
     * 
     * @return string
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * Defines global template's layout
     * 
     * @param string $layout Template name
     * @param string $layoutModule Just a basename of that layout inside theme's folder
     * @return \Krystal\Application\View\ViewManager
     */
    public function setLayout($layout, $layoutModule = null)
    {
        $this->layout = $layout;
        $this->layoutModule = $layoutModule;

        return $this;
    }

    /**
     * Cancels defined layout if present
     * 
     * @return \Krystal\Application\View\ViewManager
     */
    public function disableLayout()
    {
        $this->layout = null;
        return $this;
    }

    /**
     * Checks whether global layout has been defined before
     * 
     * @return boolean
     */
    public function hasLayout()
    {
        return $this->layout !== null;
    }

    /**
     * Defines whether output compression should be done
     * 
     * @param boolean $compress
     * @return \Krystal\Application\View\ViewManager
     */
    public function setCompress($compress)
    {
        $this->compress = (bool) $compress;
        return $this;
    }

    /**
     * Adds a variable
     * 
     * @param string $name Variable name in view
     * @param mixed $value A variable itself
     * @return \Krystal\Application\View\ViewManager
     */
    public function addVariable($name, $value)
    {
        $this->variables[$name] = $value;
        return $this;
    }

    /**
     * Appends collection of variables
     * 
     * @param array $variables Collection of variables
     * @return \Krystal\Application\View\ViewManager
     */
    public function addVariables(array $variables)
    {
        foreach ($variables as $name => $value) {
            $this->addVariable($name, $value);
        }

        return $this;
    }

    /**
     * Determines whether there's at least one defined variable
     * 
     * @return boolean
     */
    public function hasVariables()
    {
        return !empty($this->variables);
    }

    /**
     * Generates URL by known controller's syntax and optional arguments
     * This should be used inside templates only
     * 
     * @return string
     */
    public function url()
    {
        $args = func_get_args();
        $controller = array_shift($args);

        return $this->urlBuilder->build($controller, $args);
    }

    /**
     * Prints a string
     * 
     * @param string $message
     * @param boolean $translate Whether to translate a string
     * @return void
     */
    public function show($message, $translate = true)
    {
        if ($translate === true) {
            $message = $this->translate($message);
        }

        echo $message;
    }

    /**
     * Creates path to view template
     * 
     * @param string $dir Base directory when assets are stored
     * @param string $path
     * @param string $module
     * @param string $theme
     * @return string
     */
    private function createPath($dir, $path, $module, $theme = null)
    {
        if ($theme !== null) {
            return sprintf('%s/%s/%s/%s/%s', $path, self::TEMPLATE_PARAM_MODULES_DIR, $module, $dir, $theme);
        } else {
            return sprintf('%s/%s/%s/%s', $path, self::TEMPLATE_PARAM_MODULES_DIR, $module, $dir);
        }
    }

    /**
     * Returns a theme path appending required filename
     * 
     * @param string $filename
     * @return string
     */
    public function getWithThemePath($filename)
    {
        return sprintf('%s/%s', $this->createThemePath(), $filename);
    }
    
    /**
     * Creates shared theme path
     * 
     * @param string $module
     * @param string $theme
     * @param string $base
     * @return string
     */
    private function createSharedThemePath($module, $theme, $base)
    {
        if (is_null($theme)) {
            $theme = $this->theme;
        }

        if (is_null($module)) {
            $module = $this->module;
        }

        return sprintf('%s/%s/%s/%s', $base, $module, self::TEMPLATE_PARAM_BASE_DIR, $theme);
    }

    /**
     * Creates theme URL
     * 
     * @param string $module
     * @param string $theme
     * @return string
     */
    public function createThemeUrl($module = null, $theme = null)
    {
        return $this->createSharedThemePath($module, $theme, '/'.self::TEMPLATE_PARAM_MODULES_DIR);
    }

    /**
     * Resolves a base path
     * 
     * @param string $module
     * @param string $theme Optionally a theme can be overridden
     * @return string
     */
    public function createThemePath($module = null, $theme = null)
    {
        return $this->createSharedThemePath($module, $theme, $this->moduleDir);
    }

    /**
     * Creates URL for asset
     * 
     * @param string $module
     * @param string $path Optional path to be appended
     * @return string
     */
    public function createAssetUrl($module = null, $path = null)
    {
        if (is_null($module)) {
            $module = $this->module;
        }

        return sprintf('/%s/%s/%s', self::TEMPLATE_PARAM_MODULES_DIR, $module, self::TEMPLATE_PARAM_ASSETS_DIR) . $path;
    }

    /**
     * Returns asset path by module and its nested path
     * 
     * @param string $path
     * @param string $module Optionally default module can be replaced by another one
     * @param boolean $absolute Whether returned path should be absolute or relative
     * @param boolean $fromAssets Whether to use assets directory or theme's internal one
     * @return string
     */
    private function createAssetPath($path, $module, $absolute, $fromAssets)
    {
        $baseUrl = '';

        if ($absolute === true) {
            $url = $baseUrl;
        } else {
            $url = null;
        }

        // If module isn't provided, then current one used by default
        if (is_null($module)) {
            $module = $this->module;
        }

        if ($fromAssets !== false) {
            $dir = self::TEMPLATE_PARAM_ASSETS_DIR;
            $theme = null;
        } else {
            $dir = self::TEMPLATE_PARAM_BASE_DIR;
            $theme = $this->theme;
        }

        return $url.sprintf('%s/%s', $this->createPath($dir, $baseUrl, $module, $theme), $path);
    }

    /**
     * Returns full path to a file by its name
     * 
     * @param string $name File's basename
     * @param string $module Module name
     * @return string
     */
    private function createInclusionPath($name, $module = null)
    {
        if (is_null($module)) {
            $module = $this->module;
        }

        $base = $this->createPath(self::TEMPLATE_PARAM_BASE_DIR, dirname($this->moduleDir), $module, $this->theme);
        $path = sprintf('%s.%s', $base . \DIRECTORY_SEPARATOR . $name, self::TEMPLATE_PARAM_EXTENSION);

        return $path;
    }

    /**
     * Generates a path to module asset file
     * 
     * @param string $path The target asset path
     * @param string $module Optionally module name can be overridden. By default the current is used
     * @param boolean $absolute Whether path must be absolute or not
     * @return string
     */
    public function moduleAsset($asset, $module = null, $absolute = false)
    {
        return $this->createAssetPath($asset, $module, $absolute, true);
    }

    /**
     * Generates a full path to an asset
     * 
     * @param string $asset Path to the target asset
     * @param string $module
     * @param boolean $absolute Whether path must be absolute or not
     * @return string
     */
    public function asset($asset, $module = null, $absolute = false)
    {
        return $this->createAssetPath($asset, $module, $absolute, false);
    }

    /**
     * Checks whether framework-compliant template file exists
     * 
     * @param string $template
     * @return boolean
     */
    public function templateExists($template)
    {
        return is_file($this->createInclusionPath($template));
    }

    /**
     * Includes a file a returns its content as a string
     * 
     * @param string $file Path to the file
     * @return string
     */
    private function createFileContent($file)
    {
        ob_start();
        extract($this->variables);
        include($file);

        return ob_get_clean();
    }

    /**
     * Returns content of glued layout and its fragment
     * 
     * @param string $layout Path to a layout
     * @param string $fragment Path to a fragment
     * @return string
     */
    private function createContentWithLayout($layout, $fragment)
    {
        // Create and append $fragment variable to the shared view stack
        $this->variables[self::TEMPLATE_PARAM_FRAGMENT_VAR_NAME] = $this->createFileContent($fragment);

        return $this->createFileContent($layout);
    }

    /**
     * Passes variables and renders a template. If there's attached layout, then renders it with that layout
     * 
     * @param string $template Template's name without extension in themes directory
     * @param array $vars
     * @throws \RuntimeException if wrong template file provided
     * @return string
     */
    public function render($template, array $vars = array())
    {
        if (!$this->templateExists($template)) {
            throw new RuntimeException(sprintf('Invalid template path provided : %s', $template));
        }

        // Template file
        $file = $this->createInclusionPath($template);

        $this->addVariables($vars);

        if ($this->hasLayout()) {
            $layout = $this->createInclusionPath($this->layout, $this->layoutModule);

            $content = $this->createContentWithLayout($layout, $file);
        } else {
            $content = $this->createFileContent($file);
        }

        // Compress if needed
        if ($this->compress === true) {
            $compressor = new HtmlCompressor();
            $content = $compressor->compress($content);
        }

        return $content;
    }

    /**
     * Renders a template with custom Module and its theme
     * 
     * @param string $module
     * @param string $theme Theme directory name
     * @param string $template Template file to be rendered
     * @param array $vars Variables to be passed to a template
     * @return string
     */
    public function renderRaw($module, $theme, $template, array $vars = array())
    {
        // Save initial values before overriding theme
        $initialLayout = $this->layout;
        $initialModule = $this->module;
        $initialTheme = $this->theme;

        // Configure view with new values
        $this->setModule($module)
             ->setTheme($theme)
             ->disableLayout();

        $response = $this->render($template, $vars);

        // Restore initial values now
        $this->layout = $initialLayout;
        $this->module = $initialModule;
        $this->theme = $initialTheme;

        return $response;
    }

    /**
     * Load several blocks at once
     * 
     * @param array $blocks
     * @return void
     */
    public function loadBlocks(array $blocks)
    {
        return array_walk($blocks, array($this, 'loadBlock'));
    }

    /**
     * Loads a block
     * 
     * @param string $name
     * @param array $vars Additional variables if needed
     * @return void
     */
    public function loadBlock($name, array $vars = array())
    {
        extract(array_replace_recursive($vars, $this->variables));

        $file = $this->getBlockBag()->getBlockFile($name);

        if (is_file($file)) {
            include($file);
        } else {
            return false;
        }
    }

    /**
     * Translates a string
     * 
     * @param string $message
     * @return string
     */
    public function translate()
    {
        $args = func_get_args();
        return call_user_func_array(array($this->translator, 'translate'), $args);
    }

    /**
     * Translates array values
     * 
     * @param array $messages
     * @return array
     */
    public function translateArray(array $messages)
    {
        return $this->translator->translateArray($messages);
    }
}

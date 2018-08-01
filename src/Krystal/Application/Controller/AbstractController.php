<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\Controller;

use Krystal\InstanceManager\ServiceLocatorInterface;
use Krystal\Application\View\Resolver\ModuleResolver;
use Krystal\Db\Filter\FilterableServiceInterface;
use Krystal\Db\Filter\FilterInvoker;
use RuntimeException;

abstract class AbstractController
{
    /**
     * Service Locator
     * 
     * @var \Krystal\InstanceManager\ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * Current module name
     * 
     * @var string
     */
    protected $moduleName;

    /**
     * Controller options
     * 
     * @var array
     */
    protected $options = array();

    /**
     * Whether execution is halted
     * 
     * @var boolean
     */
    protected $halted = false;

    /**
     * State initialization
     * 
     * @param \Krystal\InstanceManager\ServiceLocatorInterface $serviceLocator
     * @param string $moduleName Target module we are in
     * @param array $options
     * @return void
     */
    final public function __construct(ServiceLocatorInterface $serviceLocator, $moduleName, array $options)
    {
        $this->serviceLocator = $serviceLocator;
        $this->moduleName = $moduleName;
        $this->options = $options;
    }

    /**
     * Prepares a response as JSON string setting corresponding headers
     * 
     * @param array $data
     * @param integer Native encoding options
     * @return string
     */
    final protected function json(array $data, $options = 0)
    {
        // Set JSON header
        $this->response->respondAsJson();

        return json_encode($data, $options);
    }

    /**
     * Creates a mapper
     * 
     * @param string $namespace
     * @return \Krystal\Db\Sql\AbstractMapper
     */
    final protected function createMapper($namespace)
    {
        return $this->mapperFactory->build($namespace);
    }

    /**
     * Creates validation instance
     * 
     * @param array $config
     * @return \Krystal\Validate\ValidatorChain
     */
    final protected function createValidator(array $params)
    {
        return $this->validatorFactory->build($params);
    }

    /**
     * Creates URL
     * 
     * @param string $controller
     * @param array $args
     * @param integer $index
     * @return string
     */
    final protected function createUrl($controller, array $args = array(), $index = 0)
    {
        return $this->urlBuilder->createUrl($controller, $args, $index);
    }

    /**
     * Redirects to given route
     * 
     * @param string $route Target route
     * @throws \RuntimeException if unknown route supplied
     * @return void
     */
    final protected function redirectToRoute($route)
    {
        $url = $this->urlBuilder->build($route);

        if ($url !== null) {
            $this->response->redirect($url);
        } else {
            throw new RuntimeException(sprintf('Unknown route supplied for redirection "%s"', $route));
        }
    }

    /**
     * Forwards to another controller's action
     * 
     * @param string $route Framework-compliant route
     * @param array $args Optional arguments
     * @return string
     */
    final protected function forward($route, array $args = array())
    {
        return $this->dispatcher->forward($route, $args);
    }

    /**
     * Applies filtering method from the service that implements corresponding interface
     * That's just a shortcut method
     * 
     * @param \Krystal\Db\Filter\FilterableServiceInterface $service A service which implement this interface
     * @param integer $perPageCount Items per page to display
     * @param string $route Base route
     * @return array
     */
    final protected function getQueryFilter(FilterableServiceInterface $service, $perPageCount, $route)
    {
        $invoker = new FilterInvoker($this->request->getQuery(), $route);
        return $invoker->invoke($service, $perPageCount);
    }

    /**
     * Returns a service of current module
     * 
     * @param string $service
     * @return object
     */
    final protected function getModuleService($service)
    {
        return $this->getService($this->moduleName, $service);
    }

    /**
     * A shortcut for grabbing services
     * 
     * @param string $module
     * @param string $service
     * @return object
     */
    final protected function getService($module, $service)
    {
        return $this->moduleManager->getModule($module)->getService($service);
    }

    /**
     * Handle calls to undefined class properties
     * 
     * The point of this magic method is simple - avoid calling $this->serviceLocator->whatever, but call $this->whatever instead
     * It simplifies access to a service locator, when dependencies were injected manually
     * 
     * @param string $property
     * @return mixed
     */
    public function __get($property)
    {
        if ($this->serviceLocator->has($property)) {
            return $this->serviceLocator->get($property);
        } else {
            trigger_error(sprintf('Attempted to grab non-existing service or accessed to undefined property "%s"', $property));
            return null;
        }
    }

    /**
     * Halts the execution
     * 
     * @return void
     */
    public function halt()
    {
        $this->halted = true;
    }

    /**
     * Checks whether execution is halted
     * 
     * @return boolean
     */
    public function isHalted()
    {
        return $this->halted;
    }

    /**
     * Checks whether current controller has an option in its route definition
     * 
     * @param string $option
     * @return boolean
     */
    final protected function hasOption($option)
    {
        $options = $this->getOptions();
        return isset($options[$option]);
    }

    /**
     * Returns current options for a matched controller
     * 
     * @param string $key
     * @return array
     */
    public function getOptions($key = null)
    {
        if ($key == null) {
            return $this->options;
        } else {
            return $this->options[$key];
        }
    }

    /**
     * Returns current module where this controller belongs
     * 
     * @return string
     */
    public function getModule()
    {
        return $this->moduleName;
    }

    /**
     * Halts controller's execution on demand
     * 
     * @return void
     */
    final protected function haltOnDemand()
    {
        switch (true) {

            // If at least one of below conditions is true, then $this->halt() is called
            case $this->hasOption('secure') && $this->getOptions('secure') == true && !$this->request->isSecure():
            case $this->hasOption('ajax') && $this->getOptions('ajax') == true && !$this->request->isAjax():
            case $this->hasOption('method') && (strtoupper($this->getOptions('method')) != $this->request->getMethod()):

            $this->halt();
        }
    }

    /**
     * Load translation messages from all available modules
     * 
     * @param string $language
     * @return void
     */
    private function loadTranslationMessages($language)
    {
        // Reset any previous translations if any
        $this->moduleManager->clearTranslations();
        $this->translator->reset();

        // Now load new ones
        $this->moduleManager->loadAllTranslations($language);
        $this->translator->extend($this->moduleManager->getTranslations());
    }

    /**
     * Loads default translations if default language is defined
     * 
     * @return void
     */
    private function loadDefaultTranslations()
    {
        $language = $this->appConfig->getLanguage();

        if ($language !== null) {
            $this->loadTranslationMessages($language);
        }
    }

    /**
     * Load translation messages
     * 
     * @param string $language
     * @return boolean
     */
    final protected function loadTranslations($language)
    {
        // Don't load the same language twice, if that's the case
        if ($this->appConfig->getLanguage() !== $language) {

            $this->appConfig->setLanguage($language);
            $this->loadTranslationMessages($language);
            return true;

        } else {
            return false;
        }
    }

    /**
     * Invoked right after class instantiation
     * 
     * @param string $action Current action to be executed
     * @return void
     */
    final public function initialize($action)
    {
        $this->haltOnDemand();

        // Configure view with defaults
        $this->view->setModule($this->getModule())
                   ->setTheme($this->appConfig->getTheme());

        if (method_exists($this, 'bootstrap')) {
            $this->bootstrap($action);
        }

        if (method_exists($this, 'onAuth')) {
            $this->onAuth();
        }

        $this->loadDefaultTranslations();
    }
}

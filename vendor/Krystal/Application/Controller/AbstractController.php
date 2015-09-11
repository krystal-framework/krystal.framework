<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
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
	 * Returns view path appending target one
	 * 
	 * @param string $path
	 * @param string $module
	 * @param string $theme
	 * @return string
	 */
	final protected function getWithViewPath($path, $module, $theme)
	{
		return $this->getViewPath($module, $theme) . $path;
	}

	/**
	 * Returns view path to given module
	 * 
	 * @param string $module
	 * @param string $theme
	 * @return string
	 */
	final protected function getViewPath($module, $theme)
	{
		return sprintf('%s/%s/%s/%s', $this->appConfig->getModulesDir(), $module, 'View/Template', $theme);
	}

	/**
	 * Returns asset path
	 * 
	 * @param string
	 * @param string $module Current one is used If omitted 
	 * @return string
	 */
	final protected function getWithAssetPath($path, $module = null)
	{
		if (is_null($module)) {
			$module = $this->moduleName;
		}

		return sprintf('/module/%s/Assets', $module) . $path;
	}

	/**
	 * Returns with theme path
	 * 
	 * @param string $path
	 * @param string $module Optionally module can be replaced
	 * @return string
	 */
	final protected function getWithThemePath($path, $module = null)
	{
		if (is_null($module)) {
			$module = $this->moduleName;
		}

		return sprintf('/module/%s/View/Template/%s/%s', $module, $this->getResolverThemeName(), $path);
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
	 * Returns resolver module's name to be used when rendering
	 * 
	 * @return string
	 */
	protected function getResolverModuleName()
	{
		return $this->getModule();
	}
	
	/**
	 * Returns theme name to be used when rendering a template from within view
	 * 
	 * @return string
	 */
	protected function getResolverThemeName()
	{
		return $this->appConfig->getTheme();
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
	 * Invoked right after class instantiation
	 * 
	 * @return void
	 */
	final public function initialize()
	{
		$this->haltOnDemand();

		// Now tweak the view
		$resolver = new ModuleResolver($this->request, $this->appConfig->getModulesDir(), $this->getResolverModuleName(), $this->getResolverThemeName());
		$this->view->setResolver($resolver);

		if (method_exists($this, 'bootstrap')) {
			$this->bootstrap();
		}

		if (method_exists($this, 'onAuth')) {
			$this->onAuth();
		}
	}
}

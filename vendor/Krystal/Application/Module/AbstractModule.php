<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\Module;

use Krystal\Application\Module\ModuleManagerInterface;
use Krystal\Application\AppConfigInterface;
use Krystal\InstanceManager\ServiceLocatorInterface;
use RuntimeException;
use LogicException;

/**
 * Each application's module must inherit from this one
 */
abstract class AbstractModule
{
	/**
	 * Module manager
	 * 
	 * @var \Krystal\Application\Module\ModuleManagerInterface
	 */
	protected $moduleManager;

	/**
	 * Application configuration container
	 * 
	 * @var \Krystal\Application\AppConfigInterface
	 */
	protected $appConfig;

	/**
	 * Service locator
	 * 
	 * @var \Krystal\InstanceManager\ServiceLocatorInterface
	 */
	protected $serviceLocator;

	/**
	 * Configuration data returned by getConfigData() method
	 * Initially must be null, for the cache's purposes
	 * 
	 * @var array
	 */
	protected $config = null;

	/**
	 * Service providers returned by getServiceProviders() method
	 * Initially must be null, for the cache's purposes
	 * 
	 * @var array
	 */
	protected $serviceProviders = null;

	/**
	 * Path provider for descendant's module
	 * 
	 * @var \Krystal\Application\Module\PathProviderInterface
	 */
	protected $pathProvider;

	/**
	 * State initialization
	 * 
	 * @param \Krystal\Application\Module\ModuleManagerInterface $moduleManeger
	 * @param \Krystal\InstanceManager\ServiceLocatorInterface $serviceLocator
	 * @param \Krystal\Application\AppConfigInterface $appConfig
	 * @param \Krystal\Application\Module\PathProviderInterface $pathProvider
	 * @return void
	 */
	public function __construct(
		ModuleManagerInterface $moduleManager, 
		ServiceLocatorInterface $serviceLocator, 
		AppConfigInterface $appConfig, 
		PathProviderInterface $pathProvider
	){
		$this->moduleManager = $moduleManager;
		$this->serviceLocator = $serviceLocator;
		$this->appConfig = $appConfig;
		$this->pathProvider = $pathProvider;
	}

	/**
	 * Returns path provider for current module
	 * 
	 * @return \Krystal\Application\Module\PathProvider
	 */
	final protected function getPathProvider()
	{
		return $this->pathProvider;
	}

	/**
	 * Safely loads an array from a file
	 * 
	 * @param string $file
	 * @return array
	 */
	final protected function loadArray($file)
	{
		if (is_file($file)) {
			$array = include($file);
			
			if (is_array($array)) {
				return $array;
			} else {
				trigger_error(sprintf('Included file "%s" should return an array not %s', $file, gettype($array)));
			}
			
		} else {
			
			return array();
		}
	}

	/**
	 * Returns service locator
	 * 
	 * @return \Krystal\InstanceManager\ServiceLocator
	 */
	final public function getServiceLocator()
	{
		return $this->serviceLocator;
	}

	/**
	 * Returns module manager
	 * 
	 * @return \Krystal\Application\Module\ModuleManager
	 */
	final public function getModuleManager()
	{
		return $this->moduleManager;
	}

	/**
	 * Returns application configuration provider
	 * 
	 * @return \Krystal\Application\AppConfig
	 */
	final public function getAppConfig()
	{
		return $this->appConfig;
	}

	/**
	 * Returns module configuration key
	 * 
	 * @param string $key Optionally can be filtered by existing key
	 * @throws \LogicException When getConfigData() doesn't return array, but another type of data
	 * @throws \RuntimeException If module doesn't implement getConfigData() method
	 * @return array
	 */
	final public function getConfig($key = null)
	{
		if (method_exists($this, 'getConfigData')) {
			if ($this->config === null) {
				$this->config = $this->getConfigData();
				if (!is_array($this->config)) {
					throw new LogicException('Configuration provider should return an array');
				}
			}

			if (!is_null($key)) {
				if (isset($this->config[$key])) {
					return $this->config[$key];
				} else {
					trigger_error('Attempted to read non-existing configuration key');
				}
			} else {
				return $this->config;
			}

		} else {
			throw new RuntimeException(sprintf(
				'If you want to read configuration from modules, you should implement provideConfig() method that returns an array in %s', null
			));
		}
	}

	/**
	 * Checks whether either configuration key exists or config is not empty
	 * 
	 * @param string $key
	 * @return boolean Depending on success
	 */
	final public function hasConfig($key = null)
	{
		$config = $this->getConfig();
		
		if (is_null($key)) {
			return !empty($config);
		} else {
			return array_key_exists($key, $config);
		}
	}

	/**
	 * Returns a service
	 * 
	 * @param string $name Service provider's name
	 * @throws \RuntimeException if called when there are services
	 * @throws \LogicException if attempted to read non-existing service
	 * @return object
	 */
	final public function getService($name)
	{
		if (method_exists($this, 'getServiceProviders')) {
			
			$services = $this->getServices();
			
			if (isset($services[$name])) {
				return $services[$name];
			} else {
				throw new LogicException(sprintf(
					'Attempted to read non-existing service %s', $name
				));
			}
			
		} else {
			throw new RuntimeException(sprintf(
				'There are no services'
			));
		}
	}

	/**
	 * Checks whether we have a registered service
	 * 
	 * @param string $name
	 * @return boolean
	 */
	final public function hasService($name)
	{
		$services = $this->getServices();
		return isset($services[$name]);
	}

	/**
	 * Returns all registered service providers
	 * 
	 * @return array
	 */
	final public function getServices()
	{
		if (is_null($this->serviceProviders)) {
			$this->serviceProviders = $this->getServiceProviders();
		}
		
		return $this->serviceProviders;
	}
}

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

return array(

	'production' => false,
	'timezone' => 'UTC',

	/**
	 * Framework components configuration
	 */
	'components' => array(

		/**
		 * Session component
		 */
		'session' => array(
			'handler' => 'native',
			'cookie_params' => array(
				// Session cookie parameters can be set set
			)
		),

		/**
		 * Configuration service
		 */
		'config' => array(
			'adapter' => 'sql',
			'options' => array(
				'connection' => 'mysql',
				'table' => 'config'
			)
		),

		/**
		 * Cache component
		 */
		'cache' => array(
			// By default setting up file-based caching engine
			'engine' => 'file',
			'options' => array(
				'file' => 'data/cache/cache.data'
			),
		),

		/**
		 * CAPTCHA configuration
		 */
		'captcha' => array(
			'type' => 'standard',
			'options' => array(
				'font' => 'Duality.ttf'
				// Default options can be overridden here
			)
		),

		/**
		 * Autoloader configuration
		 */
		'autoload' => array(
			'psr-0'	=> array(
				dirname(__DIR__) . '/vendor',
				dirname(__DIR__) . '/module',
			)
		),
		
		/**
		 * Configuration for view manager
		 */
		'view' => array(
			'theme' => 'welcome'
		),

		/**
		 * Translator configuration
		 */
		'translator' => array(
			// Default language
			'default' => 'en',
		),

		/**
		 * Param bag which holds application-level parameters
		 * This values can be accessed in controllers, like $this->paramBag->get(..key..)
		 */
		'paramBag' => array(
		),

		/**
		 * Router configuration
		 */
		'router' => array(
			'default' => 'Site:Welcome@notFoundAction',
		),

		/**
		 * Form validation component. It has two options only
		 */
		'validator' => array(
			'translate' => true,
			'render' => 'MessagesOnly',
		),

		/**
		 * Database component provider
		 * It needs to be configured here and accessed in mappers
		 */
		'db' => array(
			'mysql' => array(
				'host' => '127.0.0.1',
				'dbname' => 'test',
				'username' => 'root',
				'password' => '',
			)
		),
		
		/**
		 * MapperFactory which relies on previous db section
		 */
		'mapperFactory' => array(
			'connection' => 'mysql'
		),

		/**
		 * Pagination component used in data mappers. 
		 */
		'paginator' => array(
			'style' => 'Digg',
		)
	)
);

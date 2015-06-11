<?php


/* Most options are already set by default, therefore they are hidden */
return array(
	
	'production' => false,
	'timezone' => 'UTC',
	
	/**
	 * Framework components configuration
	 */
	'components' => array(
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
			'default' => 'Demo:Welcome@notFoundAction',
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
		 * 
		 * Like this: $this->db->...
		 */
		'db' => array(
			'mysql' => array(
				'connection' => array(
					'host' => '127.0.0.1',
					'dbname' => 'Your_db_name_here',
					'username' => 'root',
					'password' => '',
				),
				
				'events' => array(
					'fail' => function($exception) {
						// You would want to change this in production
						//die($exception->getMessage());
					}
				)
			),
		),
		
		/**
		 * MapperFactory
		 */
		'mapperFactory' => array(
			'mysql' => array(
			),
		),
		
		/**
		 * Pagination component used in data mappers. 
		 * It's completely independent from a storage layer (be it SQL, or No-SQL, or pure array)
		 * and can be used as a standalone component as well.
		 * 
		 * You can configure default style (@see Styles)
		 * And items per page count default value, when its not specified explicitly
		 */
		'paginator' => array(
			'style' => 'Digg',
			'itemsPerPage' => 10
		),
		
		// Cookie component
		'cookie' => array(
			'ttl' => 1000,
		),
		
		// Session component
		'session' => array(
		)
	)
);

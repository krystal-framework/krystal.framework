<?php

namespace Site;

use Krystal\Application\Module\AbstractModule;

final class Module extends AbstractModule
{
	/**
	 * Returns routes of this module
	 * 
	 * @return array
	 */
	public function getRoutes()
	{
		return array(
			'/' => array(
				'controller' => 'Welcome@indexAction'
			),

			'/hello/(:var)' => array(
				'controller' => 'Welcome@helloAction',
			)
		);
	}

	/**
	 * Returns prepared service instances of this module
	 * 
	 * @return array
	 */
	public function getServiceProviders()
	{
		return array(
		
		);
	}
}

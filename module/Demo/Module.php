<?php

namespace Demo;

use Krystal\Application\Module\AbstractModule;

final class Module extends AbstractModule
{
	public function getRoutes()
	{
		return array(
			'/' => array(
				'controller' => 'Welcome@indexAction'
			),

			'/test' => array(
				'controller' => 'Welcome@testAction',
			)
		);
	}
}

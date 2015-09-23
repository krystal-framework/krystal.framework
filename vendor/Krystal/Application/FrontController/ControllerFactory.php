<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\FrontController;

use Krystal\InstanceManager\ServiceLocatorInterface;
use RuntimeException;
use DomainException;

final class ControllerFactory implements ControllerFactoryInterface
{
	/**
	 * Service locator
	 *  
	 * @var \Krystal\InstanceManager\ServiceLocatorInterface
	 */
	private $serviceLocator;

	/**
	 * State initialization
	 * 
	 * @param \Krystal\InstanceManager\ServiceLocatorInterface $serviceLocator
	 * @return void
	 */
	public function __construct(ServiceLocatorInterface $serviceLocator)
	{
		$this->serviceLocator = $serviceLocator;
	}

	/**
	 * Builds a controller instance
	 * 
	 * @param string $controller PSR-0 Compliant path
	 * @param array $options Route options passed to corresponding controller
	 * @return \Krystal\Application\Controller\AbstractController
	 */
	public function build($controller, array $options)
	{
		$class = Ns::normalize($controller);

		// PSR-0 Autoloader will do its own job by default when calling class_exists() function
		if (class_exists($class)) {

			// Target module which is going to be instantiated
			$module = Ns::extractVendorNs($controller);
			$controller = new $class($this->serviceLocator, $module, $options);

			if (method_exists($controller, 'initialize')) {

				$controller->initialize();

				if ($controller->isHalted()) {
					throw new DomainException('Controller halted its execution due to route options mismatch');
				}

				return $controller;

			} else {
				throw new RuntimeException('A base controller must be inherited');
			}

		} else {
			// A name does not match PSR-0
			trigger_error(sprintf(
				'Controller does not exist : "%s" or it does not match PSR-0', $class), E_USER_ERROR
			);
		}
	}
}

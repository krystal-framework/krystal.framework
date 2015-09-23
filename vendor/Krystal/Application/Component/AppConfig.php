<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\Component;

use Krystal\Application\AppConfig as Component;
use Krystal\InstanceManager\DependencyInjectionContainerInterface;
use Krystal\Application\InputInterface;
use Krystal\Http\RequestInterface;
use RuntimeException;

final class AppConfig implements ComponentInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function getInstance(DependencyInjectionContainerInterface $container, array $config, InputInterface $input)
	{
		if (!isset($config['components']['view']['theme'])) {
			throw new RuntimeException('You should provide a theme');
		}

		$request = $container->get('request');
		$language = $this->getLanguage($config, $request);

		$appConfig = new Component();
		$appConfig->setRootDir(isset($config['root_dir']) ? $config['root_dir'] : $request->getRootDir())
				  ->setDataDir(isset($config['data_dir']) ? $config['data_dir'] : $appConfig->getRootDir() . '/data')
				  ->setUploadsDir(isset($config['uploads_dir']) ? $config['uploads_dir'] : $appConfig->getDataDir() . '/uploads')
				  ->setLanguage($language)
				  ->setTheme($config['components']['view']['theme'])
				  ->setThemesDir(isset($config['themes_dir']) ? $config['themes_dir'] : $appConfig->getRootDir(). '/themes')
				  ->setThemeDir($appConfig->getThemesDir().'/'.$appConfig->getTheme())
				  ->setRootUrl(isset($config['root_url']) ? $config['root_url'] : $request->getBaseUrl())
				  ->setTempDir(isset($config['temp_dir']) ? $config['temp_dir'] : $appConfig->getDataDir().'/tmp')
				  ->setCacheDir(isset($config['cache_dir']) ? $config['cache_dir'] : $appConfig->getDataDir().'/cache')
				  ->setModulesDir(isset($config['module_dir']) ? $config['module_dir'] : $appConfig->getRootDir() . '/module');

		return $appConfig;
	}

	/**
	 * Returns language value to be used for request
	 * 
	 * @param array $translator Translator's configuration
	 * @param \Krystal\Http\RequestInterface $request
	 * @return string
	 */
	private function getLanguage(array $config, RequestInterface $request)
	{
		// Special configuration for language section
		if (isset($config['components']['translator'])) {

			// Just a reference as a short-hand
			$translator =& $config['components']['translator'];

			// Visitor function has a higher priority
			if (isset($translator['visitor']) && is_callable($translator['visitor'])) {
				// Invoke a visitor
				$language = $translator['visitor']($container, $config);
				// If we have a default language
			} elseif (isset($translator['default'])) {
				$language = $translator['default'];
			} else {
				// We don't have a visitor and we don't have a default language here
			}

			// Must have lower priority
			if (isset($translator['when'], $translator['when']['uri'], $translator['when']['language'])) {
				if (!is_array($translator['when']['uri'])) {
					$translator['when']['uri'] = (array) $translator['when']['uri'];
				}

				foreach ($translator['when']['uri'] as $uri) {
					if (strpos($request->getURI(), $uri) !== false) {
						$language = $translator['when']['language'];
					}
				}
			}

		} else {

			// Use default language
			$language = 'en';
		}

		return $language;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getName()
	{
		return 'appConfig';
	}
}

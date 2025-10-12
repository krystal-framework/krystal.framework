<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\Component;

use Krystal\Application\Module\ModuleManager as Component;
use Krystal\Application\Module\Loader;
use Krystal\Application\Module\CoreBag;
use Krystal\Application\InputInterface;
use Krystal\InstanceManager\DependencyInjectionContainerInterface;
use RuntimeException;

final class ModuleManager implements ComponentInterface
{
    /**
     * {@inheritDoc}
     */
    public function getInstance(DependencyInjectionContainerInterface $container, array $config, InputInterface $input)
    {
        $appConfig = $container->get('appConfig');

        // Read from configuration
        if (isset($config['components']['module_manager'])) {
            $section = &$config['components']['module_manager'];

            if (isset($section['loader'])) {
                switch ($section['loader']) {
                    case 'auto':
                        $loader = new Loader\Dir($appConfig->getModulesDir());
                    break;

                    case 'list':
                        if (isset($section['options']['modules']) && is_array($section['options']['modules'])) {
                            $loader = new Loader\StaticList($section['options']['modules']);
                        } else {
                            throw new RuntimeException('No modules provided for the list');
                        }
                    break;
                }
            } else {
                // By default
                $loader = new Loader\Dir($appConfig->getModulesDir());
            }

        } else {
            // When no configuration provided, we'd stick to defaults
            $loader = new Loader\Dir($appConfig->getModulesDir());
        }

        $moduleManager = new Component($loader, $container->getAll(), $appConfig);

        // Validate core modules, if provided
        if (isset($section, $section['core_modules'])) {
            $moduleManager->setCoreModuleNames($section['core_modules']);
        }

        $moduleManager->initialize();
        return $moduleManager;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'moduleManager';
    }
}

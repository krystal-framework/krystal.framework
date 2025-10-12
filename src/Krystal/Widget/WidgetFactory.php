<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Widget;

use Krystal\Application\InputInterface;
use Krystal\InstanceManager\DependencyInjectionContainerInterface;

final class WidgetFactory
{
    /**
     * Container instance
     * 
     * @var \Krystal\InstanceManager\DependencyInjectionContainerInterface
     */
    private $container;

    /**
     * Input instance
     * 
     * @var \Krystal\Application\InputInterface
     */
    private $input;

    /**
     * State initialization
     * 
     * @param \Krystal\InstanceManager\DependencyInjectionContainerInterface $container
     * @param \Krystal\Application\InputInterface $input
     * @return void
     */
    public function __construct(DependencyInjectionContainerInterface $container, InputInterface $input)
    {
        $this->container = $container;
        $this->input = $input;
    }

    /**
     * Builds widget instance
     * 
     * @param string $widget A compliant instance of a widget
     * @return string
     */
    public function build(WidgetInterface $widget)
    {
        return $widget->render($this->container, $this->input);
    }
}

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Widget\Breadcrumbs;

use Krystal\Application\InputInterface;
use Krystal\InstanceManager\DependencyInjectionContainerInterface;
use Krystal\Widget\WidgetInterface;

final class BreadcrumbWidget implements WidgetInterface
{
    /**
     * Attribute options
     * 
     * @var array
     */
    private $options = array();

    /**
     * State initialization
     * 
     * @param array $options
     * @return void
     */
    public function __construct(array $options = array())
    {
        $this->options = $options;
    }

    /**
     * Renders a wigdet
     * 
     * @param \Krystal\InstanceManager\DependencyInjectionContainerInterface $container
     * @param \Krystal\Application\InputInterface $input
     * @return string
     */
    public function render(DependencyInjectionContainerInterface $container, InputInterface $input)
    {
        $breadcrumbBag = $container->get('view')->getBreadcrumbBag();
        $translator = $container->get('translator');

        $maker = new BreadcrumbMaker($breadcrumbBag, $translator, $this->options);

        return $maker->render();
    }
}

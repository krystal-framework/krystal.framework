<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Widget;

use Krystal\Grid\Grid;
use Krystal\Application\InputInterface;
use Krystal\InstanceManager\DependencyInjectionContainerInterface;

final class GridWidget implements WidgetInterface
{
    /**
     * Data source
     * 
     * @var array
     */
    private $data;

    /**
     * Column options
     * 
     * @var array
     */
    private $options;

    /**
     * Optional route
     * 
     * @var string
     */
    private $route;

    /**
     * State initialization
     * 
     * @param array $data Data source
     * @param array $options Widget options
     * @param string $route Optional route
     * @return void
     */
    public function __construct(array $data, array $options, $route = null)
    {
        $this->data = $data;
        $this->options = $options;
        $this->route = $route;
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
        $query = $input->getQuery();
        $translator = $container->get('translator');

        return Grid::render($this->data, $this->options, $translator, $this->route, $query);
    }
}

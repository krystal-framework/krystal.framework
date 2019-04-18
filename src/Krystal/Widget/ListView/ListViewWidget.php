<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Widget\ListView;

use Krystal\Application\InputInterface;
use Krystal\InstanceManager\DependencyInjectionContainerInterface;
use Krystal\Widget\WidgetInterface;

final class ListViewWidget implements WidgetInterface
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
     * State initialization
     * 
     * @param mixed $data Data source
     * @param array $options Widget options
     * @throws \RuntimeException if not array-like data provided
     * @return void
     */
    public function __construct($data, array $options)
    {
        $this->data = $data;
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
        $translator = $container->get('translator');

        $maker = new ListViewMaker($this->data, $this->options, $translator);

        return $maker->render();
    }
}

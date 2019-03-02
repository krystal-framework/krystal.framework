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

use Krystal\Grid\ListView;
use Krystal\Application\InputInterface;
use Krystal\InstanceManager\DependencyInjectionContainerInterface;

final class ListWidget implements WidgetInterface
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
     * @param array $data Data source
     * @param array $options Widget options
     * @return void
     */
    public function __construct(array $data, array $options)
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
        $widget = new ListView($this->data, $this->options, $translator);

        return $widget->render();
    }
}

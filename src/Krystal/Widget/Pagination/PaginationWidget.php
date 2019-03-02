<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Widget\Pagination;

use Krystal\Application\InputInterface;
use Krystal\InstanceManager\DependencyInjectionContainerInterface;
use Krystal\Widget\WidgetInterface;
use Krystal\Paginate\PaginatorInterface;

final class PaginationWidget implements WidgetInterface
{
    /**
     * Any compliant pagination instance
     * 
     * @var \Krystal\Paginate\PaginatorInterface
     */
    private $paginator;

    /**
     * Attribute options
     * 
     * @var array
     */
    private $options = array();
    
    /**
     * State initialization
     * 
     * @param \Krystal\Paginate\PaginatorInterface $paginator
     * @param array $options
     * @return void
     */
    public function __construct(PaginatorInterface $paginator, array $options = array())
    {
        $this->paginator = $paginator;
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
        $maker = new PaginationMaker($this->paginator, $this->options);
        return $maker->render();
    }
}

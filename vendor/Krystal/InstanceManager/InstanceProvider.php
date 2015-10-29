<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\InstanceManager;

/* Gets a list of class names and returns only available ones */
final class InstanceProvider implements InstanceProviderInterface
{
    /**
     * A pair of class name => arguments
     * 
     * @var array
     */
    private $data;

    /**
     * State initialization
     * 
     * @param array $data
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Returns all instances that are available
     * 
     * @return array
     */
    public function getAll()
    {
        $instances = array();
        $builder = new InstanceBuilder();

        foreach ($this->data as $className => $args) {
            if (class_exists($className)) {

                $instance = $builder->build($className, $args);
                array_push($instances, $instance);
            }
        }

        return $instances;
    }
}

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

interface InstanceBuilderInterface
{
    /**
     * Builds an instance of a class passing arguments to its constructor
     * 
     * @param string $class PSR-0 compliant class name
     * @param array $args Arguments to be passed to class's contructor
     * @throws \RuntimeException If attempting to build non-existing class
     * @return object
     */
    public function build($class, array $args);
}

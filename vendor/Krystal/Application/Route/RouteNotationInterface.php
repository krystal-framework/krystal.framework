<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\Route;

interface RouteNotationInterface
{
    /**
     * Converts short controller action into parameters that can be passed to call_user_func_array
     * 
     * @param string $notation
     * @return array
     */
    public function toArgs($notation);

    /**
     * Converts route notation syntax back to PSR-0 compliant
     * 
     * @param string $notation
     * @return array
     */
    public function toCompliant($notation);

    /**
     * Converts notated syntax to compliant
     * 
     * @param string $class
     * @return string
     */
    public function toClassPath($class);    
}

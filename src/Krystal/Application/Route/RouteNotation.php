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

final class RouteNotation implements RouteNotationInterface
{
    const ROUTE_SYNTAX_DELIMITER = ':';
    const ROUTE_SYNTAX_SEPARATOR = '@';
    const ROUTE_CONTROLLER_DIR = 'Controller';

    /**
     * Converts short controller action into parameters that can be passed to call_user_func_array
     * 
     * @param string $notation
     * @return array
     */
    public function toArgs($notation)
    {
        $target = $this->toClassPath($notation);
        return explode(self::ROUTE_SYNTAX_SEPARATOR, $target);
    }

    /**
     * Converts route notation syntax back to PSR-0 compliant
     * 
     * @param string $notation
     * @return array
     */
    public function toCompliant($notation)
    {
        $chunks = explode(self::ROUTE_SYNTAX_SEPARATOR, $notation);

        // Save the action into a variable
        $action = $chunks[1];
        // Break what we have according to separators now
        $parts = explode(':', $chunks[0]);
        // Take a module and save it now
        $module = array_shift($parts);
        // It's time to build PSR-0 compliant path
        $path = sprintf('/%s/%s/%s', $module, self::ROUTE_CONTROLLER_DIR, implode('/', $parts));
        
        return array($path => $action);
    }

    /**
     * Converts notated syntax to compliant
     * 
     * @param string $class
     * @return string
     */
    public function toClassPath($class)
    {
        $parts = explode(self::ROUTE_SYNTAX_DELIMITER, $class);

        $module = $parts[0];
        unset($parts[0]);

        $path = sprintf('/%s/%s/%s', $module, self::ROUTE_CONTROLLER_DIR, implode('/', $parts));

        return $path;
    }
}

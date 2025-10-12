<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\Route;

interface UrlBuilderInterface
{
    /**
     * Creates query URL
     * 
     * @param string $controller
     * @param array $args
     * @param string $index
     * @param boolean $decode
     * @return string
     */
    public function createQueryUrl($controller, array $args = array(), $index = 0, $decode = true);

    /**
     * Creates URL
     * 
     * @param string $controller In <Module:Path@method> format
     * @param array $args A collection of arguments to be passed to the method
     * @param integer $index Index in case, one controller action has more than one route
     * @return string
     */
    public function createUrl($controller, array $args = array(), $index = 0);

    /**
     * Builds an URL
     * 
     * @param string $controller Controller name in format <Module>:<Controller>@<Action>
     * @param array $vars
     * @return string|boolean False on failure
     */
    public function build($controller, array $vars = array());
}

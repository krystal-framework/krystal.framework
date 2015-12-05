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

interface UrlBuilderInterface
{
    /**
     * Builds an URL
     * 
     * @param string $controller Controller name in format <Module>:<Controller>@<Action>
     * @param array $vars
     * @return string|boolean False on failure
     */
    public function build($controller, array $vars = array());
}

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

interface RouterInterface
{
    /**
     * Matches a URI string against a route map
     * 
     * @param string $uri The actual segment to match against
     * @param array $map Target route map to compare against
     * @return boolean
     */
    public function match($uri, array $map);
}

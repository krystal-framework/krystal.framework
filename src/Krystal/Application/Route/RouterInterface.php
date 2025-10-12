<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\Route;

interface RouterInterface
{
    /**
     * Process redirect
     * 
     * @param string $uri Current URI
     * @param array $map Map of old => new relations
     * @return void
     */
    public function processRedirect($uri, array $map);

    /**
     * Matches a URI string against a route map
     * 
     * @param string $uri The actual segment to match against
     * @param array $map Target route map to compare against
     * @return boolean
     */
    public function match($uri, array $map);
}

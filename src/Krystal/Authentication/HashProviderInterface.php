<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Authentication;

interface HashProviderInterface
{
    /**
     * Hashes a string
     * 
     * @param string $key
     * @return string
     */
    public function hash($string);
}

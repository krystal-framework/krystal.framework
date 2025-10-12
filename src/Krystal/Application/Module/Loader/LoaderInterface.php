<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\Module\Loader;

interface LoaderInterface
{
    /**
     * Fetches available collection of modules
     * 
     * @return array
     */
    public function getModules();
}

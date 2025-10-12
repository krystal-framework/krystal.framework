<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Http\FileTransfer;

interface InputInterface
{
    /**
     * Checks whether named input field is empty. If empty then checks whole array
     * 
     * @param string $name Input name can be defined optionally
     * @return boolean
     */
    public function hasFiles($name = null);

    /**
     * Return all files. Optionally filtered by named input
     * 
     * @param string $name Name filter
     * @throws RuntimeException if $name isn't valid field
     * @return object
     */
    public function getFiles($name = null); 
}

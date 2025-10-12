<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application;

final class MagicQuotesFilter
{
    /**
     * Checks whether magic quotes are deprecated
     * 
     * @return boolean
     */
    private function isDeprecated()
    {
        return function_exists('set_magic_quotes_runtime');
    }

    /**
     * Deactivates magic quotes at runtime
     * 
     * @return void
     */
    public function deactivate()
    {
        // This function is deprecated as of 5.4
        if (!$this->isDeprecated()) {
            set_magic_quotes_runtime(false);
        }
    }

    /**
     * Checks whether magic quotes are enabled
     * 
     * @return boolean
     */
    public function enabled()
    {
        if ($this->isDeprecated()) {
            return (bool) get_magic_quotes_gpc();
        } else {
            return false;
        }
    }

    /**
     * Recursively filter slashes in array
     * 
     * @param mixed $value
     * @return array
     */
    public function filter($value)
    {
        return is_array($value) ? array_map(array($this, __FUNCTION__), $value) : stripslashes($value);
    }
}

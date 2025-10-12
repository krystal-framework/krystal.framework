<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\I18n;

interface TranslatorInterface
{
    /**
     * Clears a dictionary
     * 
     * @return void
     */
    public function reset();

    /**
     * Translate array values if possible
     * 
     * @param array $array
     * @param mixed $module Optional module constraint
     * @throws \InvalidArgumentException if at least one array's value isn't a string
     * @return array
     */
    public function translateArray(array $array, $module = null);

    /**
     * Translates message string from a dictionary, optionally filtering by a module
     * 
     * @param string $module Current module
     * @param string $message Message string to be translated
     * @param array $arguments String variables, if available
     * @throws \RuntimeException if trying to translate a string from non-loaded module
     * @return string
     */
    public function translateFrom($module, $message, array $arguments = array());

    /**
     * Translates a string
     * 
     * @return string
     */
    public function translate();

    /**
     * Extends first language array ($data)
     * 
     * @param array [$args]
     * @return void
     */
    public function extend();

    /**
     * Check whether a message translation exists in a stack
     * 
     * @param string $message The target string
     * @param string $module Optional module constraint
     * @return boolean
     */
    public function has($message, $module = null);
}

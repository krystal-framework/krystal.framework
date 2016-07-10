<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
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
     * @return array
     */
    public function translateArray(array $array);

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
     * Check whether a string exists in a stack
     * 
     * @param string $string The target string
     * @return boolean
     */
    public function has($string);
}

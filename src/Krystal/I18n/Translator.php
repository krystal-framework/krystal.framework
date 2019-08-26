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

use InvalidArgumentException;
use RuntimeException;

final class Translator implements TranslatorInterface
{
    /**
     * Original messages with their associated translations
     * 
     * @var array
     */
    private $dictionary = array();

    /**
     * State initialization
     * 
     * @param array $dictionary
     * @return void
     */
    public function __construct(array $dictionary = array())
    {
        if (!empty($dictionary)) {
            $this->extend($dictionary);
        }
    }

    /**
     * Filter message variables
     * 
     * @param array $arguments
     * @return array
     */
    private static function filterVariables(array $arguments)
    {
        // The variables we are going to deal with
        $variables = array();

        // Iterate over arguments we have (message is stripped away)
        foreach ($arguments as $argument) {
            if (is_array($argument)) {
                // Array supplied as second argument, so here we don't need anything to do
                $variables = $argument;
                break;
            }

            // Only strings and integers are supported to be passed as arguments
            if (is_string($argument) || is_numeric($argument)) {
                array_push($variables, $argument);
            }
        }
        
        return $variables;
    }

    /**
     * Clears a dictionary
     * 
     * @return void
     */
    public function reset()
    {
        $this->dictionary = array();
    }

    /**
     * Extends first language array ($data)
     * 
     * @param array [$args]
     * @return void
     */
    public function extend()
    {
        foreach (func_get_args() as $array) {
            $this->dictionary = array_merge($this->dictionary, $array);
        }
    }

    /**
     * Check whether a message translation exists in a stack
     * 
     * @param string $message The target string
     * @param string $module Optional module constraint
     * @return boolean
     */
    public function has($message, $module = null)
    {
        if ($module === null) {
            // Global check
            foreach ($this->dictionary as $target => $translations){
                // Linear search
                if (isset($translations[$message])) {
                    return true;
                }

                // Not found by default
                return false;
            }

        } else {
            return isset($this->dictionary[$module][$message]);
        }
    }

    /**
     * Translate array values if possible
     * 
     * @param array $array
     * @param mixed $module Optional module constraint
     * @throws \InvalidArgumentException if at least one array's value isn't a string
     * @return array
     */
    public function translateArray(array $array, $module = null)
    {
        $result = array();

        foreach ($array as $key => $value) {
            // Group translation
            if (is_array($value)) {
                $key = $this->translateFrom($module, $key);

                // If group is not created yet, create it
                if (!isset($result[$key])){
                    $result[$key] = array();
                }

                foreach($value as $index => $inner) {
                    $result[$key][$index] = $this->translateFrom($module, $inner);
                }
                
            } else if (is_scalar($value)) {
                $result[$key] = $this->translateFrom($module, $value);
            } else {
                throw new InvalidArgumentException('Invalid array supplied');
            }
        }

        return $result;
    }

    /**
     * Translates message string from a dictionary, optionally filtering by a module
     * 
     * @param string $module Current module
     * @param string $message Message string to be translated
     * @param array $arguments String variables, if available
     * @throws \RuntimeException if trying to translate a string from non-loaded module
     * @return string
     */
    public function translateFrom($module, $message, array $arguments = array())
    {
        // If NULL supplied, then stop and return original message
        if (is_null($message)) {
            return $message;
        }

        // Ensure the proper message data type supplied
        if (!is_scalar($message)) {
            return null;
        }

        $arguments = self::filterVariables($arguments);

        // Don't process anything if a dictionary is empty
        if (empty($this->dictionary)) {
            return vsprintf($message, $arguments);
        }

        // Immediately stop, if invalid module name provided
        if ($module !== null && !isset($this->dictionary[$module])) {
            throw new RuntimeException(sprintf(
                'The module "%s" is not loaded. You should either use global look up or provide module name which is loaded', $module
            ));
        }

        // Look up in a module only
        if ($module !== null) {
            // Grab the message if available in current module
            $source = isset($this->dictionary[$module][$message]) ? $this->dictionary[$module][$message] : $message;
        } else {
            // Global look up
            foreach ($this->dictionary as $target => $translations) {
                if (isset($translations[$message])) {
                    $source = $translations[$message];

                    // No reason to continue search, since we just found a translation
                    break;
                } else {
                    $source = $message;
                }
            }
        }

        return vsprintf($source, $arguments);
    }

    /**
     * Translates a string
     * 
     * @param string $default Default message
     * @param string|array $placeholders Number of placeholder according to specified string
     * @return string Translated string
     */
    public function translate()
    {
        $arguments = func_get_args();
        $message = array_shift($arguments);

        return $this->translateFrom(null, $message, $arguments);
    }
}

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
     * Check whether a string exists in a stack
     * 
     * @param string $string The target string
     * @return boolean
     */
    public function has($string)
    {
        return in_array($string, array_keys($this->dictionary));
    }

    /**
     * Translate array values if possible
     * 
     * @param array $array
     * @throws \InvalidArgumentException if at least one array's value isn't a string
     * @return array
     */
    public function translateArray(array $array)
    {
        $result = array();

        foreach ($array as $key => $value) {
            // Group translation
            if (is_array($value)) {
                $key = $this->translate($key);

                // If group is not created yet, create it
                if (!isset($result[$key])){
                    $result[$key] = array();
                }

                foreach($value as $index => $inner) {
                    $result[$key][$index] = $this->translate($inner);
                }
                
            } else if (is_scalar($value)) {
                $result[$key] = $this->translate($value);
            } else {
                throw new InvalidArgumentException('Invalid array supplied');
            }
        }

        return $result;
    }

    /**
     * Translates a single string
     * 
     * @param string $message Message string to be translated
     * @param array $arguments String variables, if any
     * @return string
     */
    private function translateSingle($message, array $arguments)
    {
        if (is_null($message)) {
            return $message;
        }

        // Don't process anything if a dictionary is empty
        if (empty($this->dictionary)) {
            return vsprintf($message, $arguments);
        }

        // Ensure the proper message received
        if (!is_scalar($message)) {
            return;
        }

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

        if (isset($this->dictionary[$message])) {
            return vsprintf($this->dictionary[$message], $variables);
        }

        return vsprintf($message, $variables);
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
        
        return $this->translateSingle($message, $arguments);
    }
}

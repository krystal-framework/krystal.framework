<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Stdlib;

use LogicException;

abstract class ArrayUtils
{
    /**
     * Merges two arrays removing keys
     * 
     * @param array $first
     * @param array $second
     * @param array $keys Keys to be removed after merging
     * @return array
     */
    public static function mergeWithout(array $first, array $second, array $keys)
    {
        $result = array_merge($first, $second);
        return self::arrayWithout($result, $keys);
    }

    /**
     * Determines whether all keys exist in a collection
     * 
     * @param array $collection
     * @param array $keys Keys to be checked for existence
     * @return boolean
     */
    public static function keysExist(array $collection, array $keys)
    {
        $collection = array_flip($collection);

        foreach ($keys as $key) {
            if (!in_array($key, $collection)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Filters an array by matching keys only
     * 
     * @param array $array Target array
     * @param array $keys Filtering keys
     * @return array
     */
    public static function arrayOnlyWith(array $array, array $keys)
    {
        $result = array();

        if (!self::isSequential($array)) {
            foreach ($array as $key => $value) {
                if (in_array($key, $keys)) {
                    $result[$key] = $value;
                }
            }
        } else {
            foreach ($array as $value) {
                if (in_array($value, $keys)) {
                    $result[] = $value;
                }
            }
        }

        return $result;
    }

    /**
     * Combines two arrays even with different length
     * 
     * @param array $first
     * @param array $second
     * @param string $replacement Replacement for
     * @param boolean $order Whether to merge first with second or vice versa
     * @return array
     */
    public static function arrayCombine(array $first, array $second, $replacement = null, $order = true)
    {
        // Fix for different length
        if (count($first) !== count($second)) {
            $input = array($first, $second);
            $count = array_map('count', $input);

            // Find the largest and lowest array index
            $min = array_keys($count , max($count));
            $max = array_keys($count , min($count));

            // Find indexes
            $min = $min[0];
            $max = $max[0];

            $largest = $input[$min];
            $smallest = $input[$max];

            // Now fix the length
            foreach ($largest as $key => $value) {
                if (!isset($smallest[$key])) {
                    $smallest[$key] = $replacement; 
                }
            }

            $first = $smallest;
            $second = $largest;
        }

        if ($order === true) {
            return array_combine($second, $first);
        } else {
            return array_combine($first, $second);
        }
    }

    /**
     * Prepends a pair to associative array by reference
     * 
     * @param array $array Target array
     * @param string $key Key to be prepended
     * @param mixed $value Value to be prepended
     * @return void
     */
    public static function assocPrepend(array &$array, $key, $value)
    {
        $array = array_reverse($array, true);
        $array[$key] = $value;
        $array = array_reverse($array, true);
    }

    /**
     * Turns array's row into a list
     * 
     * @param array $array Target array
     * @param string $key Column's name to be used as a key
     * @param string $value Column's name to be used as a value
     * @return array
     */
    public static function arrayList(array $array, $key, $value)
    {
        $result = array();

        foreach ($array as $row) {
            if (isset($row[$key], $row[$value])) {
                // References
                $name =& $row[$key];
                $text =& $row[$value];

                $result[$name] = $text;

            } else {
                trigger_error(sprintf('Nested arrays must container both %s and %s', $key, $value));
            }
        }

        return $result;
    }

    /**
     * Returns a copy of an array without keys
     * 
     * @param array $array Target array
     * @param array $keys Keys to be removed
     * @return array
     */
    public static function arrayWithout(array $array, array $keys)
    {
        foreach ($keys as $key) {
            if (array_key_exists($key, $array)) {
                unset($array[$key]);
            }
        }

        return $array;
    }

    /**
     * Search data by given value inside array recursively
     * 
     * @param array $haystack
     * @param string $needle
     * @return array|boolean
     */
    public static function search(array $haystack, $needle)
    {
        foreach ($haystack as $key => $value) {
            if (is_array($value)) {
                $target = self::search($value, $needle); 
            } else {
                $target = '';
            }

            if ($needle === $value || ($target != false && $target != null)) {
                if ($target == null) {
                    return array($key);
                }
                return array_merge(array($key), $target);
            }
        }

        return false;
    }

    /**
     * Array unique for multi-dimensional arrays
     * 
     * @param array $array
     * @return array
     */
    public static function arrayUnique(array $array)
    {
        // Serialize each array's value
        foreach ($array as &$value) {
            $value = serialize($value);
        }

        // Now remove duplicate strings
        $array = array_unique($array);

        // Now restore to its previous state with removed strings
        foreach ($array as &$value) {
            $value = unserialize($value);
        }

        return $array;
    }

    /**
     * Checks whether target array has at least one another array key's value
     * 
     * @param array $array Target array
     * @return boolean
     */
    public static function hasAtLeastOneArrayValue(array $array)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks whether array is sequential (i.e not associative)
     * 
     * @param array $array Target array
     * @return boolean
     */
    public static function isSequential(array $array)
    {
        $count = count($array);

        for ($i = 0; $i < $count; $i++) {
            if (!isset($array[$i]) && !array_key_exists($i, $array)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Determines whether array is associative
     * 
     * @param array $array
     * @return boolean
     */
    public static function isAssoc(array $array)
    {
        return !self::isSequential($array);
    }
}

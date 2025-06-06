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
use InvalidArgumentException;
use Closure;

abstract class ArrayUtils
{
    /**
     * Returns unique array signature
     * 
     * @param array $array Target array
     * @return string
     */
    public static function fingerprint(array $array)
    {
        $raw = serialize($array);
        return hash('crc32b', $raw);
    }

    /**
     * Categories an array dropping by partition and adding count key
     * 
     * @param array $rows Raw result set
     * @param string $key Partition key
     * @return array
     */
    public static function categorize(array $rows, $key)
    {
        $dropdown = self::arrayPartition($rows, $key, false);

        $output = array();

        foreach ($dropdown as $partition => $rows) {
            $output[] = array(
                'name' => $partition,
                'count' => count($dropdown[$partition]),
                'items' => $rows
            );
        }

        return $output;
    }

    /**
     * Normalize arguments from another variadic function
     * 
     * @param array $args Arguments of another function
     * @param boolean $strict Whether strict validation is enabled
     * @return array
     */
    public static function parseArgs(array $args, $strict = true)
    {
        // First argument
        $first = array_shift($args);

        // If arguments provided
        if (array_key_exists(0, $args)) {
            // Count
            $count = count($args);

            // If in strict mode, ensure we get expected behavior
            if ($strict == true && is_array($args[0]) && $count > 1) {
                throw new InvalidArgumentException('In strict mode, you can either provide an array of arguments or a list of arguments without array');
            }

            // Turn into one structure
            if ($count >= 1) {
                $params = is_array($args[0]) ? $args[0] : array($args[0]);
            }

            if ($count > 1) {
                $params = $args;
            }

        } else {
            // No arguments provided
            $params = array();
        }

        return array(
            'master' => $first,
            'arguments' => $params
        );
    }

    /**
     * Checks whether variable is array-like
     * 
     * @param mixed $var
     * @return boolean
     */
    public static function isIterable($var)
    {
        return is_array($var) ||
               ($var instanceof \ArrayAccess  ||
                $var instanceof \Traversable  ||
                $var instanceof \Serializable ||
                $var instanceof \Countable);
    }

    /**
     * Unsets an item from an array by its value, not by key
     * 
     * @param array $array
     * @param mixed $value Value to be removed
     * @return array
     */
    public static function unsetByValue(array $array, $value)
    {
        $key = array_search($value, $array);

        if (false !== $key) {
            unset($array[$key]);
        }

        return $array;
    }

    /**
     * Drops a raw result-set into partitions and creates inner array with key => value pairs
     * 
     * @param array $raw
     * @param string $partition The key to be considered as partition
     * @param string $key
     * @param string $value
     * @return array
     */
    public static function arrayDropdown(array $raw, $partition, $key, $value)
    {
        $output = array();

        foreach (self::arrayPartition($raw, $partition) as $target => $data) {
            foreach ($data as $innerKey => $innerValue) {
                if (isset($innerValue[$key], $innerValue[$value])) {
                    $output[$target][$innerValue[$key]] = $innerValue[$value];
                }
            }
        }

        return $output;
    }

    /**
     * Drops an array into partitions
     * 
     * @param array $raw Raw array
     * @param string $key The key to be considered as partition
     * @param boolean $keepKey Whether to keep key in output
     * @throws \LogicException if unknown partition key supplied
     * @return array Dropped array
     */
    public static function arrayPartition(array $raw, $key, $keepKey = true)
    {
        $result = [];

        foreach ($raw as $collection) {
            // Determine if key exists in array or object
            if (is_array($collection)) {
                $exists = array_key_exists($key, $collection);
            } elseif (is_object($collection)) {
                if ($collection instanceof VirtualEntity) {
                    $exists = isset($collection[$key]);
                } else {
                    $exists = property_exists($collection, $key);
                }
            } else {
                throw new InvalidArgumentException('Each collection must be an array or object. Got: ' . gettype($collection));
            }

            if (!$exists) {
                throw new LogicException(sprintf(
                    'The key "%s" does not exist in provided collection', $key
                ));
            }

            $module = $collection[$key];

            if (!$keepKey) {
                unset($collection[$key]);
            }

            $result[$module][] = $collection;
        }

        return $result;
    }

    /**
     * Recoveries missing array keys
     * 
     * @param array $array Target array
     * @param array $keys Missing array keys to be recovered
     * @param mixed $value Value to be recovered
     * @return array
     */
    public static function arrayRecovery(array $array, array $keys, $value)
    {
        foreach ($keys as $key) {
            if (!isset($array[$key])) {
                $array[$key] = $value;
            }
        }

        return $array;
    }

    /**
     * Duplicate array values into keys
     * 
     * @param array $data
     * @param boolean $valueModue Whether to duplicate values, not keys
     * @return array
     */
    public static function valuefy(array $data, $valueModue = true)
    {
        if ($valueModue === true) {
            $values = array_values($data);
        } else {
            $values = array_keys($data);
        }

        return array_combine($values, $values);
    }

    /**
     * Sum columns with averages
     * 
     * @param array $entities A collection of data
     * @param array $averages Columns that need to be counted as average ones
     * @param integer $precision The number of decimal digits to round to
     * @return array
     */
    public static function sumColumnsWithAverages(array $entities, array $averages = array(), $precision = 2)
    {
        // Count the sum of all columns
        $sum = self::sumColumns($entities);

        if (!empty($averages)) {
            $count = count($entities);

            // Make sure that the division by zero won't occur
            if ($count === 0) {
                $count = 1;
            }

            foreach ($averages as $average) {
                if (isset($sum[$average])) {
                    if ($precision !== false) {
                    } else {
                        $sum[$average] = $sum[$average] / $count;
                    }
                    
                }
            }
        }

        return $sum;
    }

    /**
     * Round array values recursively
     * 
     * @param array $data Target array
     * @param integer $precision The number of decimal digits to round to
     * @return array
     */
    public static function roundValues(array $data, $precision = 2)
    {
        return self::filterValuesRecursively($data, function($value) use ($precision) {
            $value = is_numeric($value) ? round($value, $precision) : $value;
            $value = empty($value) ? 0 : $value;

            return $value;
        });
    }

    /**
     * Returns all column names from two dimensional array validating by count
     * 
     * @param array $data
     * @return array|boolean
     */
    public static function arrayColumns(array $data)
    {
        // Count the amount of values from the source input
        $count = count($data);

        if ($count === 0) {
            return false;
        }

        if ($count === 1) {
            return array_keys($data[0]);
        }

        if (isset($data[0])) {
            return array_keys($data[0]);
        } else {
            return false;
        }
    }

    /**
     * Counts a total sum of each collection
     * 
     * @param array $data
     * @return array
     */
    public static function sumColumns(array $data)
    {
        $columns = self::arrayColumns($data);

        if ($columns !== false){
            return self::columnSum($data, $columns);
        } else {
            return false;
        }
    }

    /**
     * Counts column sum
     * 
     * @param array $data
     * @param array $columns
     * @return array
     */
    public static function columnSum(array $data, array $columns)
    {
        $result = array();

        foreach ($columns as $column) {
            foreach ($data as $collection) {
                if (isset($collection[$column])) {
                    if (isset($result[$column])) {
                        $result[$column] += $collection[$column];
                    } else {
                        $result[$column] = $collection[$column];
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Filter array values applying a callback function that returns a value
     * 
     * @param mixed $array
     * @param \Closure A callback function that returns a filtered value
     * @return mixed
     */
    public static function filterValuesRecursively(array $array, Closure $callback = null)
    {
        foreach ($array as $index => $value) {
            if (is_array($value)) {
                $array[$index] = call_user_func(__METHOD__, $value, $callback);
            } else {
                $array[$index] = call_user_func($callback, $value);
            }
        }

        return $array;
    }

    /**
     * Filters an array applying a callback function
     * 
     * @param array $array Target array
     * @param \Closure A callback function that returns a filtered array
     * @return array
     */
    public static function filterArray(array $array, Closure $callback)
    {
        foreach ($array as $index => $collection) {
            $array[$index] = call_user_func($callback, $collection);
        }

        return $array;
    }

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
        if (count($collection) !== count($keys)) {
            return false;
        }

        return !array_diff_key(array_flip($keys), $collection);
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
            if (array_key_exists($key, $row) && array_key_exists($value, $row)) {
                // References
                $name =& $row[$key];
                $text =& $row[$value];

                $result[$name] = $text;

            } else {
                trigger_error(sprintf('Nested arrays must contain both %s and %s', $key, $value));
            }
        }

        return $result;
    }

    /**
     * Returns a copy of an array without keys. Handles two dimensional arrays as well
     * 
     * @param array $array Target array
     * @param array $keys Keys to be removed
     * @return array
     */
    public static function arrayWithout(array $array, array $keys)
    {
        // Shared filter function
        $filter = function(array $array, array $keys) {
            foreach ($keys as $key) {
                if (array_key_exists($key, $array)) {
                    unset($array[$key]);
                }
            }

            return $array;
        };

        if (self::hasAllArrayValues($array)) {
            // Apply on nested arrays as well
            return self::filterArray($array, function($collection) use ($keys, $filter){
                return $filter($collection, $keys);
            });
        } else {
            return $filter($array, $keys);
        }
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
     * Checks whether array values are arrays
     * 
     * @param array $array Target array
     * @return boolean
     */
    public static function hasAllArrayValues(array $array)
    {
        foreach ($array as $key => $value) {
            if (!is_array($value)) {
                return false;
            }
        }

        return true;
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

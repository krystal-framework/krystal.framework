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
     * Generate a fingerprint hash for a given array.
     *
     * This method serializes the provided array and then computes a CRC32b hash.
     * It is useful for quickly generating lightweight identifiers or checksums
     * for arrays (e.g., to detect changes or ensure integrity).
     *
     * @param array $array The input array to fingerprint.
     *
     * @return string A CRC32b hash string representing the fingerprint of the array.
     */
    public static function fingerprint(array $array)
    {
        $raw = serialize($array);
        return hash('crc32b', $raw);
    }

    /**
     * Categorize an array of rows by a given key.
     *
     * This method groups rows by the specified key and returns
     * an array of categories, each containing:
     *  - `name`  : The partition value (category name).
     *  - `count` : Number of items in this category.
     *  - `items` : The rows belonging to this category.
     *
     * Internally it relies on {@see self::arrayPartition()}.
     *
     * @param array $rows A list of associative arrays (rows).
     * @param string|int $key The array key used to categorize rows.
     *
     * @return array An array of categories, each with keys: `name`, `count`, and `items`.
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
     * Determine whether a variable is iterable.
     *
     * This method checks if the given variable is:
     *  - an array, or
     *  - an object implementing one of the following interfaces:
     *      - {@see \ArrayAccess}
     *      - {@see \Traversable}
     *      - {@see \Serializable}
     *      - {@see \Countable}
     *
     * @param mixed $var The variable to check.
     *
     * @return bool True if the variable can be iterated, false otherwise.
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
     * Remove the first occurrence of a value from an array.
     *
     * Searches the array for the given value, unsets it if found,
     * and returns the resulting array. If the value does not exist,
     * the original array is returned unchanged.
     *
     * @param array $array The input array.
     * @param mixed $value The value to remove.
     *
     * @return array The array without the first occurrence of the given value.
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
     * Partition a raw result set into grouped dropdown-style arrays.
     *
     * Groups rows by a partition key and creates inner arrays with
     * `key => value` pairs suitable for dropdowns or select lists.
     *
     * @param array  $raw The raw result set (array of associative arrays or objects).
     * @param string $partition The field name used for partitioning.
     * @param string $key The field name used as the array key.
     * @param string $value The field name used as the array value.
     *
     * @return array A nested array grouped by partition, each containing key => value pairs.
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
     * Partition an array (or collection of objects) by a specified key.
     *
     * Each element of the input is assigned to a group based on the value
     * of the given key. Optionally, the partition key can be preserved
     * or removed from each grouped item.
     *
     * @param array $raw The raw array (rows as arrays or objects).
     * @param string $key The field/property used for partitioning.
     * @param boolean $keepKey Whether to keep the partition key in each item (default: true).
     *
     * @throws InvalidArgumentException If an element is not an array or object.
     * @throws LogicException If the partition key does not exist in an element.
     *
     * @return array An array of groups indexed by the partition value.
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
     * Ensures that specified keys exist in an array, filling in missing ones with a default value.
     *
     * Iterates over the provided list of keys and checks if each exists in the array.
     * If a key is missing, it will be added with the given fallback value.
     *
     * @param array $array The array to recover keys in.
     * @param array $keys The list of keys that must be present.
     * @param mixed $value The default value assigned to missing keys.
     *
     * @return array The array with all required keys ensured.
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
     * Converts an array into a key-value map where both keys and values are identical.
     *
     * If $valueModule is true, the values of the input array are used.
     * If false, the keys of the input array are used instead.
     *
     * @param array $data The input array.
     * @param bool  $valueMode  Whether to use array values (true) or array keys (false).
     *
     * @return array An associative array where keys and values are the same.
     */
    public static function valuefy(array $data, $valueMode = true)
    {
        if ($valueMode === true) {
            $values = array_values($data);
        } else {
            $values = array_keys($data);
        }

        return array_combine($values, $values);
    }

    /**
     * Calculates the sum of all columns in a dataset, with optional averages for specific columns.
     *
     * This method extends {@see ArrayUtils::sumColumns()} by allowing certain
     * columns to be averaged instead of summed. The precision of averages can
     * be controlled by the $precision argument.
     *
     * Note: If $precision is set to `false`, no rounding is applied.  
     * If it’s numeric, the average is rounded to that number of decimal places.
     *
     * @param array $entities  The dataset, where each element is an associative array.
     * @param array $averages  List of column names to calculate averages for.
     * @param int|false $precision Number of decimal places for averages, or false for raw division.
     *
     * @return array The resulting array of summed values, with averages for specified columns.
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
     * Calculates the sum of all numeric columns in a dataset.
     *
     * This method extracts all column names using {@see ArrayUtils::arrayColumns()}
     * and then delegates the summation to {@see ArrayUtils::columnSum()}.
     *
     * @param array $data The dataset, where each element is an associative array.
     *
     * @return array|false An associative array of column sums, or false if no columns could be determined.
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
     * Calculates the sum of values for specified columns across a dataset.
     *
     * Iterates over a multidimensional array (e.g., result set) and
     * accumulates totals for the given column names.
     *
     * @param array $data The dataset, where each element is an associative array.
     * @param array $columns The list of column names to sum.
     *
     * @return array An associative array of column names with their summed values.
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
     * Recursively rounds all numeric values in a dataset.
     *
     * Traverses through the array and applies rounding to every numeric value
     * using the specified precision. Non-numeric values remain unchanged,
     * and empty values are normalized to `0`.
     *
     * @param array $data The dataset to process.
     * @param int $precision The number of decimal places to round to. Default is 2.
     *
     * @return array The dataset with all numeric values rounded.
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
     * Extracts column names (keys) from the first row of a dataset.
     *
     * This method inspects the first element of a multidimensional array
     * and returns its keys, which can be treated as column names.
     * Returns `false` if the dataset is empty or invalid.
     *
     * @param array $data The dataset, where each element is an associative array.
     *
     * @return array|false A list of column names, or false if no columns are found.
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
     * Apply a callback function to all values in a multidimensional array recursively.
     *
     * Iterates over each element in the array:
     *  - If the value is an array, the function is applied recursively.
     *  - Otherwise, the provided callback is applied to the value.
     *
     * @param array $array The input array to process.
     * @param Closure $callback The callback function to apply on each non-array value.
     *
     * @return array The array with all values transformed by the callback.
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
     * Apply a callback function to each element in an array (shallow).
     *
     * Unlike PHP’s built-in {@see array_map()}, this method always passes
     * the element itself (not multiple arrays in parallel) and replaces
     * the array’s values with the callback’s return value.
     *
     * @param array $array The array to process.
     * @param Closure $callback The callback to apply to each element.
     *
     * @return array The array with transformed values.
     */
    public static function filterArray(array $array, Closure $callback)
    {
        foreach ($array as $index => $collection) {
            $array[$index] = call_user_func($callback, $collection);
        }

        return $array;
    }

    /**
     * Merges two arrays and removes the specified keys from the result.
     *
     * This method first performs a standard array merge, then filters out
     * any unwanted keys using {@see ArrayUtils::arrayWithout()}.
     *
     * @param array $first  The first array to merge.
     * @param array $second The second array to merge.
     * @param array $keys   The keys to remove from the merged array.
     *
     * @return array The merged array with the specified keys removed.
     */
    public static function mergeWithout(array $first, array $second, array $keys)
    {
        $result = array_merge($first, $second);
        return self::arrayWithout($result, $keys);
    }

    /**
     * Check whether an array contains exactly the given set of keys.
     *
     * This method returns true only if:
     *  - The number of keys in the collection matches the number of expected keys.
     *  - All expected keys exist in the collection.
     *
     * Useful when validating input arrays that must match a strict schema.
     *
     * @param array $collection The array to check.
     * @param array $keys       The list of required keys.
     *
     * @return bool True if the array has exactly the given keys, false otherwise.
     */
    public static function keysExist(array $collection, array $keys)
    {
        if (count($collection) !== count($keys)) {
            return false;
        }

        return !array_diff_key(array_flip($keys), $collection);
    }

    /**
     * Returns a filtered array that contains only the specified keys or values.
     *
     * - If the given array is associative (non-sequential), only the elements
     *   with the provided keys are kept.
     * - If the given array is sequential (numeric indexes), only the values
     *   that match the given keys are kept.
     *
     * @param array $array The input array (can be associative or sequential).
     * @param array $keys  The keys (for associative arrays) or values (for sequential arrays) to keep.
     *
     * @return array A filtered array containing only the requested keys/values.
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
     * Combines two arrays into an associative array with flexible options.
     *
     * - Automatically balances arrays of different lengths by filling missing values
     *   with a given replacement.
     * - Allows switching which array serves as keys and which as values.
     *
     * @param array $first The first array.
     * @param array $second The second array.
     * @param mixed $replacement Value to use when one array is shorter than the other (default: null).
     * @param bool  $order If true, `$second` is used as keys and `$first` as values;
     *                     if false, `$first` is used as keys and `$second` as values.
     *
     * @return array The combined associative array.
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
     * Prepends a key-value pair to the beginning of an associative array.
     *
     * Unlike `array_unshift()`, which only works with indexed arrays,
     * this method ensures that the new pair appears first while preserving
     * the order of existing keys.
     *
     * @param array  $array The associative array (passed by reference).
     * @param string|int $key   The key to insert.
     * @param mixed  $value The value to insert.
     *
     * @return void
     */
    public static function assocPrepend(array &$array, $key, $value)
    {
        $array = array_reverse($array, true);
        $array[$key] = $value;
        $array = array_reverse($array, true);
    }

    /**
     * Builds a key-value list from a multidimensional array.
     *
     * Iterates over a list of associative arrays and extracts
     * pairs based on the provided `$key` and `$value` indexes.
     *
     * Example:
     * [
     *   ['id' => 1, 'name' => 'Alice'],
     *   ['id' => 2, 'name' => 'Bob']
     * ]
     *
     * With `$key = 'id'` and `$value = 'name'` results in:
     * [
     *   1 => 'Alice',
     *   2 => 'Bob'
     * ]
     *
     * @param array       $array The source array of associative arrays.
     * @param string|int  $key   The index to use as array keys.
     * @param string|int  $value The index to use as array values.
     *
     * @return array The resulting key-value list.
     *
     * @triggers E_USER_NOTICE if a row does not contain the required keys.
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
     * Returns an array without the specified keys.
     *
     * Works both on simple associative arrays and on multidimensional arrays
     * (when every value is itself an array, determined by {@see hasAllArrayValues()}).
     *
     * For multidimensional arrays, the keys will be removed from every nested array.
     *
     * @param array $array The source array.
     * @param array $keys  The keys to remove.
     *
     * @return array The filtered array without the specified keys.
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
     * Recursively searches for a value within a multidimensional array.
     *
     * Returns the path (list of keys) to the first occurrence of the given needle.
     * If the needle is not found, `false` is returned.
     *
     * @param array $haystack The multidimensional array to search in.
     * @param mixed $needle   The value to search for.
     *
     * @return array|false An array of keys representing the path to the found value,
     *                     or false if the value is not found.
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
     * Removes duplicate values from a multidimensional array.
     *
     * This method uses serialization to ensure uniqueness across
     * nested arrays and complex values, not just scalars.
     *
     * @param array $array The multidimensional array to filter.
     *
     * @return array The array with duplicate values removed.
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
     * Checks whether all values in the given array are themselves arrays.
     *
     * Useful when validating data structures to ensure the collection
     * is a two-dimensional array.
     *
     * @param array $array The array to check.
     *
     * @return bool True if all values are arrays, false otherwise.
     */
    public static function hasAllArrayValues(array $array)
    {
        if (empty($array)) {
            return false;
        }

        foreach ($array as $key => $value) {
            if (!is_array($value)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Checks whether at least one value in the array is itself an array.
     * Useful for detecting multidimensional structures or mixed arrays.
     *
     * @param array $array The array to check.
     *
     * @return bool True if at least one value is an array, false otherwise.
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
     * Checks whether an array is sequential (i.e., a "list").
     *
     * An array is considered sequential if its keys are consecutive
     * integers starting from 0 without gaps. For example:
     *
     * Sequential: [0 => 'a', 1 => 'b', 2 => 'c']
     * Not sequential: [1 => 'a', 2 => 'b'] or ['a' => 'x', 'b' => 'y']
     *
     * @param array $array The array to check.
     *
     * @return bool True if the array is sequential, false otherwise.
     */
    public static function isSequential(array $array)
    {
        if (empty($array)) {
            return false;
        }

        $count = count($array);

        for ($i = 0; $i < $count; $i++) {
            if (!isset($array[$i]) && !array_key_exists($i, $array)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Checks whether an array is associative.
     *
     * An array is considered associative if it is not sequential, meaning
     * its keys are not strictly consecutive integers starting from 0.
     *
     * Example:
     * - Associative: ['a' => 1, 'b' => 2]
     * - Sequential:  [0 => 'a', 1 => 'b']
     *
     * @param array $array The array to check.
     *
     * @return bool True if the array is associative, false otherwise.
     */
    public static function isAssoc(array $array)
    {
        return !self::isSequential($array);
    }
}

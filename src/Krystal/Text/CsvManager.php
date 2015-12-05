<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Text;

use Krystal\Text\CsvManagerInterface;
use RuntimeException;
use LogicException;

/**
 * Comma-separated-values manager
 * 
 * @TODO: Need a method for getting maximal amount if values in sequence
 * @TODO: Rename some methods
 */
final class CsvManager implements CsvManagerInterface
{
    /**
     * Value collection
     * 
     * @var array
     */
    private $collection = array();

    /**
     * Separator 
     * 
     * @const string
     */
    const SEPARATOR = ',';

    /**
     * Class initialization
     * 
     * @param string $string
     * @return void
     */
    public function __construct($string = null)
    {
        if ($string !== null) {
            $this->loadFromString($string);
        }
    }

    /**
     * Checks whether stack is empty
     * 
     * @return boolean
     */
    public function isEmpty()
    {
        return empty($this->collection);
    }

    /**
     * Clears the stack
     * 
     * @return void
     */
    public function flush()
    {
        $this->collection = array();
    }

    /**
     * Count values
     * 
     * @param boolean $includeDuplicates Whether to include duplicate values count
     * @return integer
     */
    public function count($includeDuplicates = true)
    {
        $target = $this->collection;

        if ($includeDuplicates !== true) {
            $target = array_unique($target);
        }

        return count($target);
    }

    /**
     * Return duplicate values
     * 
     * @return array
     */
    private function getDuplicates()
    {
        return array_count_values($this->collection);
    }

    /**
     * Checks if CSV string is valid
     * 
     * @param string $target
     * @return boolean
     */
    private function isValid($target)
    {
        return true;
    }

    /**
     * Checks if a value has duplicates in a sequence
     * 
     * @param string $target
     * @throws \RuntimeException if $target does not belong to the collection
     * @return boolean     */
    public function hasDuplicates($target)
    {
        if ($this->exists($target)) {
            $duplicates = $this->getDuplicates();
            return (int) $duplicates[$target] > 1;

        } else {
            throw new RuntimeException(sprintf(
                'Attempted to read non-existing value %s', $target
            ));
        }
    }

    /**
     * Returns duplicate count by a value
     * 
     * @param string $target
     * @throws \RuntimeException if attempted to read non-existing value
     * @return integer
     */
    public function getDuplicatesCount($target)
    {
        if ($this->exists($target)) {
            if ($this->hasDuplicates($target)) {
                $duplicates = $this->getDuplicates();
                return $duplicates[$target];

            } else {
                return 0;
            }

        } else {
            throw new RuntimeException(sprintf(
                'Attempted to read non-existing value %s', $target
            ));
        }
    }

    /**
     * Appends one more value
     * 
     * @param string $value
     * @throws \LogicException if $value contains a delimiter
     * @return \Krystal\Text\CsvManager
     */
    public function append($value)
    {
        if (strpos($value, self::SEPARATOR) !== false) {
            throw new LogicException('A value cannot contain delimiter');
        }

        array_push($this->collection, $value);
        return $this;
    }

    /**
     * Append collection
     * 
     * @param array $collection
     * @return \Krystal\Text\CsvManager
     */
    public function appendCollection(array $collection)
    {
        foreach ($collection as $value) {
            $this->append($value);
        }

        return $this;
    }

    /**
     * Checks whether value exists in a stack
     * 
     * @param string $value [...]
     * @return boolean
     */
    public function exists()
    {
        foreach (func_get_args() as $value) {
            if (!in_array($value, $this->collection)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Deletes a value from the stack
     * 
     * @param string $target
     * @param boolean $keepDuplicates Whether to keep duplicate values
     * @return void
     */
    public function delete($target, $keepDuplicates = true)
    {
        // We need a copy of $this->collection, not itself:
        $array = $this->collection;

        foreach ($array as $index => $value) {
            if ($value == $target) {
                unset($array[$index]);

                if ($keepDuplicates === true) {
                    break;
                }
            }
        }

        // Now override original stack with replaced one
        $this->collection = $array;
    }

    /**
     * Returns value collection
     * 
     * @return array
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * Converts an array into a string
     * 
     * @param array $array
     * @return string
     */
    public function getAsString()
    {
        return implode(self::SEPARATOR, $this->collection);
    }

    /**
     * Builds an array from a string
     * 
     * @param string $string
     * @return void
     */
    public function loadFromString($string)
    {
        $target = array();
        $array = explode(self::SEPARATOR, $string);

        foreach ($array as $index => $value) {
            // Additional check
            if (!empty($value)) {
                // Ensure we have only clean values
                if (strpos(self::SEPARATOR, $value) === false) {
                    array_push($target, $value);
                }
            }
        }

        // Override with modified one
        $this->collection = $target;
    }
}

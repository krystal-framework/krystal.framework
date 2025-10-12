<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Text;

final class CsvLimitedTool implements CsvLimitedToolInterface
{
    /**
     * Target data we're working with
     * 
     * @var string
     */
    private $data;

    /**
     * Value separator
     * 
     * @var string
     */
    private $delemiter;

    /**
     * Limit for value amount
     * 0 Means no limit
     * 
     * @var integer
     */
    private $limit;

    /**
     * Target values
     * 
     * @var array
     */
    private $values = array();

    /**
     * State initialization
     * 
     * @param string $data Data can be loaded optionally at initialization
     * @param integer $limit Values amount limit
     * @return void
     */
    public function __construct($data = null, $limit = 0)
    {
        if ($data !== null) {
            $this->load($data);
        }

        $this->limit = (int) $limit;
    }

    /**
     * Removes last elements from values we have
     * 
     * @return void
     */
    private function removeLatest()
    {
        $iterations = count($this->values) - $this->limit;

        for ($i = 0; $i < $iterations; ++$i) {
            // Just remove last key and that's it
            array_pop($this->values);
        }
    }

    /**
     * Checks if we even have a limit on values amount
     * 
     * @return boolean
     */
    private function hasLimit()
    {
        return $this->limit !== 0;
    }

    /**
     * Checks whether write amount of values exceeds
     * 
     * @return boolean
     */
    private function isLimitExceeded()
    {
        if ($this->hasLimit()) {
            return count($this->values) > $this->limit;

        } else {
            // When no limit, it means never exceeds
            return false;
        }
    }

    /**
     * Returns values
     * 
     * @param boolean $implode Whether result must be an array or a string separated by comma
     * @return string|array
     */
    public function getValues($implode = true)
    {
        // We don't really need duplicates
        $values = array_unique($this->values);

        if ($implode === true) {
            return implode(',', $values);
        } else {
            return $values;
        }
    }

    /**
     * Loads data from a string
     * 
     * @param string $data
     * @return void
     */
    public function load($data)
    {
        // Turn current string into an array using provided separator
        $values = explode(',', $data);

        // If empty string is passed, then explode() returns false
        if ($values === false) {
            $values = array();
        } else {
            // Remove empty values from current array we have
            array_walk($values, function($value, $key) use (&$values) {
                if (empty($value)) {
                    unset($values[$key]);
                }
            });
        }

        // Done
        $this->values = $values;
    }

    /**
     * Checks whether value exists
     * 
     * @param string $value
     * @return boolean
     */
    public function exists($value)
    {
        return in_array($value, $this->values);
    }

    /**
     * Removes a value from the stack
     * 
     * @param string $value
     * @return void
     */
    public function remove($value)
    {
        $this->values = array_diff($this->values, array($value));
    }

    /**
     * Adds a value to the stack
     * 
     * @param string $value
     * @param string $function Function name which does it
     * @return boolean
     */
    private function add($value, $function)
    {
        if (!$this->exists($value)) {
            $function($this->values, $value);

            if ($this->isLimitExceeded()) {
                $this->removeLatest();
            }

            return true;
        } else {
            return false;
        }
    }

    /**
     * Prepends a value to the beginning of the stack
     * 
     * @param string $value
     * @return boolean
     */
    public function prepend($value)
    {
        return $this->add($value, 'array_unshift');
    }

    /**
     * Appends a value to the beginning of the stack
     * 
     * @param string $value Target value to be appended
     * @return boolean
     */
    public function append($value)
    {
        return $this->add($value, 'array_push');
    }
}

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Form\Selectbox;

use Krystal\Stdlib\ArrayUtils;

final class OptionBox
{
    /**
     * Collection of option entities
     * 
     * @var array
     */
    private $options = array();

    /**
     * Current active option's value
     * 
     * @var string
     */
    private $current;

    /**
     * State initialization
     * 
     * @param array $options
     * @param string $current
     * @return void
     */
    public function __construct(array $options = array(), $current = null)
    {
        $this->setOptions($options);
        $this->setCurrent($current);
    }

    /**
     * Set options
     * 
     * @param array $options
     * @return void
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    /**
     * Current active option
     * 
     * @param string $current
     * @return void
     */
    public function setCurrent($current)
    {
        $this->current = (string) $current;
    }
    
    /**
     * @return array
     */
    public function getAll()
    {
        return $this->prepare($this->options);
    }

    /**
     * Prepares raw array of options
     * 
     * @param array $options Raw options
     * @return array
     */
    private function prepare(array $options)
    {
        $result = array();

        // Determine whether $options array is sequential (i.e not associative)
        $sequential = ArrayUtils::isSequential($options);

        foreach ($options as $key => $value) {
            // By default
            $current = false;

            // In non-associative array, keys become values
            if ($sequential) {
                $key = (string) $value;
            }

            if ($this->current == $key) {
                $current = true;
            }

            $option = new OptionEntity();
            $option->setName($value)
                   ->setValue($key)
                   ->setCurrent($current);
            
            // Append prepared entity
            array_push($result, $option);
        }

        return $result;
    }
}

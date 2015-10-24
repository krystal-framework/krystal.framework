<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Form;

use Krystal\Form\Element;

class Element
{
    /**
     * Creates "Select" element with its child "Option" nodes
     * 
     * @param string $name
     * @param array $list
     * @param string $select Select child option
     * @param array $attributes Extra attributes
     * @return string
     */
    public static function select($name, array $list = array(), $selected, array $attributes = array())
    {
        $node = new Element\Select($list, $selected);

        // Define major "name" attribute
        $attributes['name'] = $name;

        return $node->render($attributes);
    }

    /**
     * Creates textarea element
     * 
     * @param string $name Element name
     * @param string $text Text to be supplied between tags
     * @param array $attributes Extra attributes
     * @return string
     */
    public static function textarea($name, $text, array $attributes = array())
    {
        $node = new Element\Textarea($text);
        return $node->render($attributes);
    }

    /**
     * Creates text input element
     * 
     * @param string $name Element name
     * @param string $value Element value
     * @param array $attributes Extra attributes
     * @return string
     */
    public static function text($name, $value, array $attributes = array())
    {
        $node = new Element\Text();

        // Define major attributes
        $attributes['name'] = $name;
        $attributes['value'] = $value;

        return $node->render($attributes);
    }
}

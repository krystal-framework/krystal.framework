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
     * Creates "Checkbox" node element
     * 
     * @param string $name Element name
     * @param boolean $checked Whether it should be checked or not
     * @param boolean $serialize Whether the element should be serializeable or not
     * @param array $attributes Extra attributes
     * @return string
     */
    public static function checkbox($name, $checked, $serialize = true, array $attributes = array())
    {
        $attributes['name'] = $name;

        $node = new Element\Checkbox($serialize, $checked);
        return $node->render($attributes);
    }

    /**
     * Creates "File" node element
     * 
     * @param string $name
     * @param array $attributes Extra attributes
     * @return string
     */
    public static function file($name, array $attributes = array())
    {
        $attributes['name'] = $name;

        $node = new Element\File();
        return $node->render($attributes);
    }

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

    /**
     * Creates color input element
     * 
     * @param string $name Element name
     * @param string $value Element value
     * @param array $attributes Extra attributes
     * @return string
     */
    public static function color($name, $value, array $attributes = array())
    {
        $node = new Element\Color();

        // Define major attributes
        $attributes['name'] = $name;
        $attributes['value'] = $value;

        return $node->render($attributes);
    }

    /**
     * Creates date input element
     * 
     * @param string $name Element name
     * @param string $value Element value
     * @param array $attributes Extra attributes
     * @return string
     */
    public static function date($name, $value, array $attributes = array())
    {
        $node = new Element\Date();

        // Define major attributes
        $attributes['name'] = $name;
        $attributes['value'] = $value;

        return $node->render($attributes);
    }

    /**
     * Creates email input element
     * 
     * @param string $name Element name
     * @param string $value Element value
     * @param array $attributes Extra attributes
     * @return string
     */
    public static function email($name, $value, array $attributes = array())
    {
        $node = new Element\Email();

        // Define major attributes
        $attributes['name'] = $name;
        $attributes['value'] = $value;

        return $node->render($attributes);
    }
}

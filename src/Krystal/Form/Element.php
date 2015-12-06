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

use Krystal\Form\Element as Node;

class Element
{
    /**
     * Creates standalone "option" element
     * 
     * @param string $value
     * @param string $text
     * @param array $attributes Extra attributes
     * @return string
     */
    public static function option($value, $text, array $attributes = array())
    {
        if (!is_null($value)) {
            $attributes['value'] = $value;
        }

        $node = new Node\Option($text);
        return $node->render($attributes);
    }

    /**
     * Creates reset button
     * 
     * @param string $text
     * @param array $attributes Extra attributes
     * @return string
     */
    public static function reset($text, array $attributes = array())
    {
        $attributes['type'] = 'reset';
        return self::button($text, $attributes);
    }

    /**
     * Creates submit button
     * 
     * @param string $text
     * @param array $attributes Extra attributes
     * @return string
     */
    public static function submit($text, array $attributes = array())
    {
        $attributes['type'] = 'submit';
        return self::button($text, $attributes);
    }

    /**
     * Creates button element
     * 
     * @param string $text
     * @param array $attributes Extra attributes
     * @return string
     */
    public static function button($text, array $attributes = array())
    {
        $node = new Node\Button($text);
        return $node->render($attributes);
    }

    /**
     * Creates "Radio" node element
     * 
     * @param string $name Element name
     * @param string $name Element value
     * @param boolean $checked Whether it should be checked or not
     * @param array $attributes Extra attributes
     * @return string
     */
    public static function radio($name, $value, $checked, array $attributes = array())
    {
        if (!is_null($name)) {
            $attributes['name'] = $name;
        }

        if (!is_null($value)) {
            $attributes['value'] = $value;
        }

        $node = new Node\Radio($checked);
        return $node->render($attributes);
    }

    /**
     * Creates "Checkbox" node element
     * 
     * @param string $name Element name
     * @param boolean $checked Whether it should be checked or not
     * @param array $attributes Extra attributes
     * @param boolean $serialize Whether the element should be serializeable or not
     * @return string
     */
    public static function checkbox($name, $checked, array $attributes = array(), $serialize = true)
    {
        if ($name !== null) {
            $attributes['name'] = $name;
        }

        $node = new Node\Checkbox($serialize, $checked);
        return $node->render($attributes);
    }

    /**
     * Creates "File" node element
     * 
     * @param string $name
     * @param string $accept What type of files the input should accept
     * @param array $attributes Extra attributes
     * @return string
     */
    public static function file($name, $accept = null, array $attributes = array())
    {
        if (!is_null($name)) {
            $attributes['name'] = $name;
        }

        if (!is_null($accept)) {
            $attributes['accept'] = $accept;
        }

        $node = new Node\File();
        return $node->render($attributes);
    }

    /**
     * Creates "Select" element with its child "Option" nodes
     * 
     * @param string $name
     * @param array $list
     * @param string|array $select Select child option
     * @param array $attributes Extra attributes
     * @return string
     */
    public static function select($name, array $list = array(), $selected, array $attributes = array())
    {
        $node = new Node\Select($list, $selected);

        if ($name !== null) {
            $attributes['name'] = $name;
        }

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
        if ($name !== null) {
            $attributes['name'] = $name;
        }

        $node = new Node\Textarea($text);
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
        $node = new Node\Text();

        if (!is_null($name)) {
            $attributes['name'] = $name;
        }

        if (!is_null($value)) {
            $attributes['value'] = $value;
        }

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
        $node = new Node\Color();

        if (!is_null($name)) {
            $attributes['name'] = $name;
        }

        if (!is_null($value)) {
            $attributes['value'] = $value;
        }

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
        $node = new Node\Date();

        if (!is_null($name)) {
            $attributes['name'] = $name;
        }

        if (!is_null($value)) {
            $attributes['value'] = $value;
        }

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
        $node = new Node\Email();

        if (!is_null($name)) {
            $attributes['name'] = $name;
        }

        if (!is_null($value)) {
            $attributes['value'] = $value;
        }

        return $node->render($attributes);
    }

    /**
     * Creates hidden input element
     * 
     * @param string $name Element name
     * @param string $value Element value
     * @param array $attributes Extra attributes
     * @return string
     */
    public static function hidden($name, $value, array $attributes = array())
    {
        $node = new Node\Hidden();

        if (!is_null($name)) {
            $attributes['name'] = $name;
        }

        if (!is_null($value)) {
            $attributes['value'] = $value;
        }

        return $node->render($attributes);
    }

    /**
     * Creates number input element
     * 
     * @param string $name Element name
     * @param string $value Element value
     * @param array $attributes Extra attributes
     * @return string
     */
    public static function number($name, $value, array $attributes = array())
    {
        $node = new Node\Number();

        if (!is_null($name)) {
            $attributes['name'] = $name;
        }

        if (!is_null($value)) {
            $attributes['value'] = $value;
        }

        return $node->render($attributes);
    }

    /**
     * Creates range input element
     * 
     * @param string $name Element name
     * @param string $value Element value
     * @param array $attributes Extra attributes
     * @return string
     */
    public static function range($name, $value, array $attributes = array())
    {
        $node = new Node\Range();

        if (!is_null($name)) {
            $attributes['name'] = $name;
        }

        if (!is_null($value)) {
            $attributes['value'] = $value;
        }

        return $node->render($attributes);
    }

    /**
     * Creates URL input element
     * 
     * @param string $name Element name
     * @param string $value Element value
     * @param array $attributes Extra attributes
     * @return string
     */
    public static function url($name, $value, array $attributes = array())
    {
        $node = new Node\Url();

        if (!is_null($name)) {
            $attributes['name'] = $name;
        }

        if (!is_null($value)) {
            $attributes['value'] = $value;
        }

        return $node->render($attributes);
    }

    /**
     * Creates password input element
     * 
     * @param string $name Element name
     * @param string $value Element value
     * @param array $attributes Extra attributes
     * @return string
     */
    public static function password($name, $value, array $attributes = array())
    {
        $node = new Node\Password();

        if (!is_null($name)) {
            $attributes['name'] = $name;
        }

        if (!is_null($value)) {
            $attributes['value'] = $value;
        }

        return $node->render($attributes);
    }

    /**
     * Creates image element
     * 
     * @param string $src Path to image
     * @param array $attributes Extra attributes
     * @return string
     */
    public static function image($src, array $attributes = array())
    {
        $node = new Node\Image();

        if (!is_null($src)) {
            $attributes['src'] = $src;
        }

        return $node->render($attributes);
    }
}
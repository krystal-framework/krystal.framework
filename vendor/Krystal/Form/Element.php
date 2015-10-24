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

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Form\Widget;

use Krystal\Form\Element\Checkbox;

class CheckBoxWidget
{
    /**
     * Renders checkbox
     * 
     * @param array $attrs Element attributes
     * @param mixed $checked Positive or negative value that reflects checking state
     * @param boolean $serialize Whether it should be serializeable or not
     * @return string
     */
    public static function render(array $attrs, $checked, $serialize = true)
    {
        $element = new Checkbox($serialize, $checked);
        return $element->render($attrs);
    }
}

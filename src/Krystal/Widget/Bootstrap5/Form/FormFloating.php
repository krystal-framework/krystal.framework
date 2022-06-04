<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Widget\Bootstrap5\Form;

use Closure;
use Krystal\Form\NodeElement;
use Krystal\Form\Element;

final class FormFloating
{
    /**
     * Renders shared wrapper
     * 
     * @param string $label
     * @param callable $elementVisitor Callback function that returns input element
     * @param string $hint Optional field hint
     * @return string
     */
    private static function createWrapper($label, Closure $elementVisitor, $hint = null)
    {
        // Unique element ID
        $id = sprintf('float-%s', md5($label));

        $float = new NodeElement();
        $float->openTag('div')
              ->addAttribute('class', 'form-floating mb-3')
              ->finalize()
              ->append($elementVisitor($id, $label))
              ->append(Element::label($label, $id));

        // Append hint, if provided
        if ($hint !== null) {
            $span = new NodeElement();
            $span->openTag('span')
                 ->addAttribute('class', 'form-text')
                 ->finalize()
                 ->setText($hint)
                 ->closeTag();

            $float->appendChild($span);
        }

        return $float->closeTag()
                     ->render();
    }

    /**
     * Creates floating input element
     * 
     * @param string $label Floating label
     * @param string $name Input's name attribute
     * @param string $value Input's value attribute
     * @param array $attributes Optional attributes
     * @param string $hint Optional field hint
     * @return string
     */
    public static function field($label, $name = null, $value = null, array $attributes = [], $hint = null)
    {
        return self::createWrapper($label, function($id, $label) use ($name, $value, $attributes){
            return Element::input($name, $value, array_merge([
                'placeholder' => $label,
                'class' => 'form-control',
                'id' => $id
            ], $attributes));
        }, $hint);
    }

    /**
     * Creates floating textarea element
     * 
     * @param string $label Floating label
     * @param string $name Input's name attribute
     * @param string $value Input's value attribute
     * @param array $attributes Optional attributes
     * @param string $hint Optional field hint
     * @return string
     */
    public static function textarea($label, $name = null, $value = null, array $attributes = [], $hint = null)
    {
        return self::createWrapper($label, function($id, $label) use ($name, $value, $attributes){
            return Element::textarea($name, $value, array_merge([
                'placeholder' => $label,
                'class' => 'form-control',
                'id' => $id
            ], $attributes));
        }, $hint);
    }

    /**
     * Creates select element
     * 
     * @param string $label Floating label
     * @param string $name
     * @param array $list
     * @param string|array $select Select child option
     * @param array $attributes Extra attributes
     * @param string|boolean $prompt Optional prompt text
     * @param \Closure $optionVisitor Optional visitor to build attributes of option tag
     * @return string
     */
    public static function select($label, $name, array $list, $selected, array $attributes = array(), $prompt = false, Closure $optionVisitor = null)
    {
        return self::createWrapper($label, function($id, $label) use ($name, $list, $selected, $attributes, $prompt, $optionVisitor){
            $attributes = array_merge([
                'aria-label' => $label,
                'class' => 'form-select',
                'id' => $id
            ], $attributes);

            return Element::select($name, $list, $selected, $attributes, $prompt, $optionVisitor);
        });
    }
}

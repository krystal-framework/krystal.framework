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

use BadMethodCallException;
use Krystal\I18n\TranslatorInterface;

/**
 * Bootstrap compliant field generator
 */
class Field
{
    /**
     * Current label text
     * 
     * @var string
     */
    private $label;

    /**
     * Current input text
     * 
     * @var string
     */
    private $input;

    /**
     * Optional translator
     * 
     * @var \Krystal\I18n\TranslatorInterface
     */
    private $translator;

    /**
     * State initialization
     * 
     * @param \Krystal\I18n\TranslatorInterface $translator
     * @return void
     */
    public function __construct(TranslatorInterface $translator = null)
    {
        if ($translator instanceof TranslatorInterface) {
            $this->translator = $translator;
        }
    }

    /**
     * Renders a field
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Handle call to undefined method
     * 
     * @param string $method
     * @param array $args
     * @throws \BadMethodCallException If called method doesn't exist in generator
     * @return mixed
     */
    public function __call($method, array $args)
    {
        $input = $this->createInputField($method, $args);

        if ($input) {
            $this->input = $input;
            return $this;
        } else {
            throw new BadMethodCallException(sprintf('Call to undefined method "%s"', $method));
        }
    }

    /**
     * Create input field
     * 
     * @param string $method Method from \Krystal\Form\Element to be called
     * @param array $args Arguments to be passed
     * @return string|boolean
     */
    private function createInputField($method, array $args)
    {
        $ns = '\Krystal\Form\Element';

        if (method_exists($ns, $method)) {
            return call_user_func_array(sprintf('%s::%s', $ns, $method), $args);
        } else {
            return false;
        }
    }

    /**
     * Creates a template for input group
     * 
     * @param string $text Label text
     * @param string $input Input text
     * @return string
     */
    protected function createInputGroup($text, $input)
    {
        if (!empty($text)) {
            $label = new NodeElement();
            $label->openTag('label')
                  ->addAttribute('class', 'col-lg-2')
                  ->setText($text)
                  ->closeTag();
        }

        $inputDiv = new NodeElement();
        $inputDiv->openTag('div')
                 ->addAttribute('class', 'col-lg-10')
                 ->setText($input)
                 ->closeTag();

        $div = new NodeElement();
        $div->openTag('div')
            ->addAttribute('class', 'form-group');

        // If we created label instance, then append it as a very first item
        if (isset($label)) {
            $div->appendChild($label);
        }

        $div->appendChild($inputDiv)
            ->closeTag();

        return $div->render();
    }

    /**
     * Translate a string if possible
     * 
     * @param string $string
     * @return string
     */
    private function translate($string)
    {
        if (!empty($string) && $this->translator instanceof TranslatorInterface) {
            return $this->translator->translate($string);
        } else {
            return $string;
        }
    }

    /**
     * Render input group
     * 
     * @return string
     */
    private function render()
    {
        $label = $this->translate($this->label);

        $output = $this->createInputGroup($label, $this->input);
        $this->clear();

        return $output;
    }

    /**
     * Clear label and input content
     * 
     * @return void
     */
    private function clear()
    {
        $this->label = null;
        $this->input = null;
    }

    /**
     * Creates text field
     * 
     * @param string $label
     * @return string
     */
    public function label($label)
    {
        $this->label = $label;
        return $this;
    }
}

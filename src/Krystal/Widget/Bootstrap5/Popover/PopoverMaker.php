<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Widget\Bootstrap5\Popover;

use Krystal\Form\NodeElement;

final class PopoverMaker
{
    /**
     * Popover custom text
     * 
     * @var string
     */
    private $text;

    /**
     * Default options
     * 
     * @var array
     */
    private $options = [
        'data-placement' => 'left',
        'data-bs-animation' => 'true',
        'data-bs-container' => 'body',
        'data-bs-delay' => '0',
        'data-bs-toggle' => 'popover',
        'data-bs-trigger' => 'hover',
        'data-bs-html' => 'false',
        'data-bs-selector' => 'false',
        'data-bs-boundary' => 'clippingParents',
        'data-bs-offset' => '[0, 8]'
    ];

    /**
     * State initialization
     * 
     * @param string $text Button text
     * @param array $options Button options
     * @return void
     */
    public function __construct($text, array $options = [])
    {
        $this->text = $text;
        $this->options = array_merge($this->options, $options);
    }

    /**
     * Render popover element
     * 
     * @return string
     */
    public function render()
    {
        return $this->renderSingle($this->options, $this->text);
    }

    /**
     * Renders popover element
     * 
     * @param array $options
     * @param string $text
     * @return string
     */
    private function renderSingle(array $options, $text)
    {
        $attributes = array_merge([
            'class' => $options['cssClass'] . ' text-nowrap',
            'tabindex' => '0',
            'role' => 'button',
            'data-bs-title' => $options['data-bs-title'],
            'data-bs-content' => $options['data-bs-content'],
        ], $options);

        unset($attributes['cssClass']);

        $a = new NodeElement();
        $a->openTag('a')
          ->addAttributes($attributes)
          ->setText($text)
          ->closeTag();

        return $a->render();
    }
}

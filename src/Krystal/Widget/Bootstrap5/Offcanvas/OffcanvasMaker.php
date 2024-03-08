<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Widget\Bootstrap5\Offcanvas;

use Krystal\Form\NodeElement;

final class OffcanvasMaker
{
    /**
     * offcanvas ID
     * 
     * @var string
     */
    private $id;

    /**
     * State initialization
     * 
     * @param string $id offcanvas id
     * @return void
     */
    public function __construct($id = null)
    {
        $this->id = $id;
    }

    /**
     * Returns id if defined, otherwise
     * 
     * @return string
     */
    public function getId()
    {
        $prefix = 'offcanvas';

        // Generate, if not provided
        if (is_null($this->id)) {
            $this->id = md5(uniqid(true));
        }

        return sprintf('%s-%s', $prefix, $this->id);
    }

    /**
     * Renders a button
     * 
     * @param string $text
     * @return string
     */
    public function renderButton($text, $class)
    {
        $button = new NodeElement();
        $button->openTag('button')
               ->addAttributes([
                'class' => $class,
                'type' => 'button',
                'data-bs-toggle' => 'offcanvas',
                'data-bs-target' => '#' . $this->getId(),
                'aria-controls' => $this->getId()
               ])
               ->setText($text)
               ->closeTag();

        return $button->render();
    }

    /**
     * Renders header
     * 
     * @param string $text
     * @return Krystal\Form\NodeElement
     */
    private function renderHeader($text)
    {
        // Main wrapper
        $div = new NodeElement();
        $div->openTag('div')
            ->addAttribute('class', 'offcanvas-header');

        // Create header
        $h5 = new NodeElement();
        $h5->openTag('h5')
           ->addAttribute('class', 'Offcanvas-title')
           ->setText($text)
           ->closeTag();

        // Create close button
        $button = new NodeElement();
        $button->openTag('button')
               ->addAttributes([
                'type' => 'button',
                'class' => 'btn-close',
                'data-bs-dismiss' => 'offcanvas',
                'aria-label' => 'Close'
               ])
               ->finalize()
               ->closeTag();

        $div->appendChild($h5)
            ->appendChild($button)
            ->closeTag();

        return $div;
    }

    /**
     * Renders body
     * 
     * @param string $content
     * @return string
     */
    private function renderBody($content)
    {
        $div = new NodeElement();
        $div->openTag('div')
            ->addAttribute('class', 'offcanvas-body')
            ->setText($content)
            ->closeTag();
        
        return $div;
    }

    /**
     * Renders widget
     * 
     * @param string $header
     * @param string $content
     * @return string
     */
    public function renderOffcanvas($header, $content)
    {
        $div = new NodeElement();
        $div->openTag('div')
            ->addAttributes([
                'class' => 'offcanvas offcanvas-start',
                'tabindex' => '-1',
                'id' => $this->getId(),
                'aria-labelledby' => 'offcanvasExampleLabel'
            ]);

        $div->appendChild($this->renderHeader($header))
            ->appendChild($this->renderBody($content));

        return $div;
    }
}

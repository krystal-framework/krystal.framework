<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Widget\Bootstrap5\Modal;

use Krystal\Form\NodeElement;

final class ModalMaker
{
    /**
     * Modal options
     * 
     * @var array
     */
    private $options = [
        'class' => 'modal fade',
        'data-bs-backdrop' => 'static',
        'data-bs-keyboard' => 'false',
        'aria-hidden' => 'true',
        'tabindex' => '-1'
    ];

    /**
     * Header text
     * 
     * @var string
     */
    private $headerText;

    /**
     * Body text
     * 
     * @var string
     */
    private $bodyText;

    /**
     * Footer text
     * 
     * @var string
     */
    private $footerText;

    /**
     * Modal identification
     * 
     * @var string
     */
    private $target;

    /**
     * State initialization
     * 
     * @param string $headerText Modal header
     * @param string $bodyText Modal body content
     * @param array $footerText Footer text
     * @param array $options
     * @return void
     */
    public function __construct($headerText, $bodyText, $footerText = null, array $options = [])
    {
        $this->headerText = $headerText;
        $this->bodyText = $bodyText;
        $this->footerText = $footerText;

        $this->options = array_merge($this->options, $options);

        if (isset($options['id'])) {
            $this->target = $options['id'];
        } else {
            $this->target = $this->generateTarget($headerText, $bodyText);
        }
    }

    /**
     * Returns target
     * 
     * @return string
     */
    public function getTarget()
    {
        return '#' . $this->target;
    }

    /**
     * Generates unique target based on header and body
     * 
     * @param string $headerText Modal header
     * @param string $bodyText Modal body content
     * @return string
     */
    private function generateTarget($headerText, $bodyText)
    {
        return sprintf('modal-%s', md5($headerText . $bodyText));
    }

    /**
     * Renders a modal
     * 
     * @return string
     */
    public function render()
    {
        return $this->createModal($this->headerText, $this->bodyText, $this->footerText);
    }

    /**
     * Creates header
     * 
     * @param string $headerText
     * @param string $labelId
     * @return string
     */
    private function createHeader($headerText, $labelId)
    {
        $header = new NodeElement();
        $header->openTag('div')
               ->addAttribute('class', 'modal-header');

        $h5 = new NodeElement();
        $h5->openTag('h5')
           ->addAttributes(['class' => 'modal-title', 'id' => $labelId])
           ->finalize()
           ->setText($headerText)
           ->closeTag();

        $attributes = [
            'type' => 'button',
            'class' => 'btn-close',
            'data-bs-dismiss' => 'modal',
            'aria-label' => 'Close'
        ];

        $closeBtn = new NodeElement();
        $closeBtn->openTag('button')
                 ->addAttributes($attributes)
                 ->finalize()
                 ->closeTag();

        $header->appendChild($h5)
               ->appendChild($closeBtn)
               ->closeTag();

        return $header;
    }

    /**
     * create modal
     * 
     * @param string $headerText Modal header
     * @param string $bodyText Modal body content
     * @param array $footerText Footer text
     * @return string
     */
    private function createModal($headerText, $bodyText, $footerText)
    {
        $wrap = new NodeElement();
        $wrap->openTag('div');

        $labelId = sprintf('label-%s', $this->target);

        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->target;
        }

        $this->options['aria-labelledby'] = $labelId;

        $wrap->addAttributes($this->options);
        $wrap->finalize();

        $dialog = new NodeElement();
        $dialog->openTag('div')
               ->addAttribute('class', 'modal-dialog');

        $content = new NodeElement();
        $content->openTag('div')
                ->addAttribute('class', 'modal-content');

        $header = $this->createHeader($headerText, $labelId);

        $body = new NodeElement();
        $body->openTag('div')
             ->addAttribute('class', 'modal-body')
             ->finalize()
             ->setText($bodyText)
             ->closeTag();

        $footer = new NodeElement();
        $footer->openTag('div')
               ->addAttribute('class', 'modal-footer')
               ->finalize()
               ->setText($footerText)
               ->closeTag();

        $content->appendChild($header)
                ->appendChild($body)
                ->appendChild($footer)
                ->closeTag();

        $dialog->appendChild($content)
               ->closeTag();

        $wrap->appendChild($dialog)
             ->closeTag();

        return $wrap;
    }
}

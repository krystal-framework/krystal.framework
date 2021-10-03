<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Widget\Bootstrap5\Alert;

use Krystal\Form\NodeElement;

final class AlertMaker
{
    /* Available types */
    const ALERT_PRIMARY = 'alert-primary';
    const ALERT_SECONDARY = 'alert-secondary';
    const ALERT_SUCCESS = 'alert-success';
    const ALERT_DANGER = 'alert-danger';
    const ALERT_WARNING = 'alert-warning';
    const ALERT_INFO = 'alert-info';
    const ALERT_LIGHT = 'alert-light';
    const ALERT_DARK = 'alert-dark';

    /**
     * Type of the alert
     * 
     * @var string
     */
    private $type;

    /**
     * HTML content of the alert
     * 
     * @var string
     */
    private $content;

    /**
     * Optional header of the Alert
     * 
     * @var string
     */
    private $header;

    /**
     * Whether alert is dismissible
     * 
     * @var boolean
     */
    private $dismissible;

    /**
     * Whether dismission must be animated
     * 
     * @var boolean
     */
    private $animate;

    /**
     * State initialization
     * 
     * @param string $type Alert type
     * @param string $content Alert HTML content
     * @param string $header Optional heading text
     * @param boolean $dismissible Whether alert is dismissible
     * @param boolean $animate Whether dismission must be animated
     * @return void
     */
    public function __construct($type, $content, $header = null, $dismissible = true, $animate = true)
    {
        $this->type = $type;
        $this->content = $content;
        $this->header = $header;
        $this->dismissible  = $dismissible;
        $this->animate = $animate;
    }

    /**
     * Renders alert widget
     * 
     * @return string
     */
    public function render()
    {
        return $this->renderAlert(
            $this->type,
            $this->content,
            $this->header,
            $this->dismissible,
            $this->animate
        );
    }

    /**
     * Creates an alert
     * 
     * @param string $type Alert type
     * @param string $content Alert HTML content
     * @param string $header Optional heading text
     * @param boolean $dismissible Whether alert is dismissible
     * @param boolean $animate Whether dismission must be animated
     * @return string Rendered alert
     */
    private function renderAlert($type, $content, $header, $dismissible, $animate)
    {
        // Alert class
        $alertClass = 'alert ' . $type;

        if ($dismissible) {
            $alertClass .= ' alert-dismissible';

            if ($animate) {
                $alertClass .= ' fade show';
            }
        }

        $div = new NodeElement();
        $div->openTag('div')
            ->addAttributes([
                'role' => 'alert',
                'class' => $alertClass
            ]);

        // Append header
        if ($header !== null) {
            $hr = new NodeElement('h4');
            $hr->openTag('h4')
                ->addAttribute('class', 'alert-heading')
                ->setText($header)
                ->closeTag();

            $div->appendChild($hr);
        }

        if ($dismissible) {
            $button = new NodeElement();
            $button->openTag('button')
                   ->addAttributes([
                    'type' => 'button',
                    'class' => 'btn-close',
                    'data-bs-dismiss' => 'alert',
                    'aria-label' => 'Close'
                   ])
                   ->finalize()
                   ->closeTag();

            $div->appendChild($button);
        }

        $div->setText($content)
            ->closeTag();

        return $div->render();
    }
}

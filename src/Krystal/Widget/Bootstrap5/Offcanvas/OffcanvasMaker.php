<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Widget\Bootstrap5\Offcanvas;

use InvalidArgumentException;
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
     * Offcanvas options
     * 
     * @var array
     */
    private $options = [];

    /**
     * Placement positions
     * 
     * @const string
     */
    const PLACEMENT_START = 'start';
    const PLACEMENT_END = 'end';
    const PLACEMENT_TOP = 'top';
    const PLACEMENT_BOTTOM = 'bottom';

    /**
     * State initialization
     * 
     * @param string $id offcanvas id
     * @param array $options
     * @return void
     */
    public function __construct($id = null, array $options = [])
    {
        $this->id = $id;
        $this->options = $options;
    }

    /**
     * Get placement value
     * 
     * @return string
     */
    private function getPlacement()
    {
        $key = 'placement';

        // Possible values
        $values = [
            self::PLACEMENT_START,
            self::PLACEMENT_END,
            self::PLACEMENT_TOP,
            self::PLACEMENT_BOTTOM
        ];

        // Is there custome defined placement?
        if (isset($this->options[$key])) {
            $this->options[$key] = strtolower($this->options[$key]);

            if (in_array($this->options[$key], $values)) {
                return $this->options[$key];
            } else {
                // Defined, but invalid value
                throw new InvalidArgumentException(sprintf(
                    'Invalid placement position supplied: %s', $this->options['placement']
                ));
            }

        } else {
            // By default
            return self::PLACEMENT_START;
        }
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
     * @param string $class Button class
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
           ->addAttribute('class', 'offcanvas-title')
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
        $attributes = [
            'class' => sprintf('offcanvas offcanvas-%s', $this->getPlacement()),
            'tabindex' => '-1',
            'id' => $this->getId(),
            'aria-labelledby' => $this->getPlacement()
        ];

        // Is body scrolling required?
        if (isset($this->options['scrolling']) && $this->options['scrolling'] == true) {
            $attributes['data-bs-scroll'] = 'true';
            $attributes['data-bs-backdrop'] = 'false';
        }

        // Is static backdrop required?
        if (isset($this->options['static']) && $this->options['static'] == true) {
            $attributes['data-bs-backdrop'] = 'static';
        }

        $div = new NodeElement();
        $div->openTag('div')
            ->addAttributes($attributes);

        $div->appendChild($this->renderHeader($header))
            ->appendChild($this->renderBody($content))
            ->closeTag();

        return $div;
    }
}

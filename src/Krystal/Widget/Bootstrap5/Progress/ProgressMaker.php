<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Widget\Bootstrap5\Progress;

use Krystal\Form\NodeElement;

final class ProgressMaker
{
    /**
     * Progress bars
     * 
     * @var array
     */
    private $bars;

    /**
     * State initialization
     * 
     * @param array $bars
     * @return void
     */
    public function __construct(array $bars)
    {
        $this->bars = $bars;
    }

    /**
     * Renders a progress bar
     * 
     * @return string
     */
    public function render()
    {
        // Default options
        $defaults = [
            'label' => false,
            'striped' => false,
            'animated' => false,
            'background' => null
        ];

        $progress = new NodeElement();
        $progress->openTag('div')
                 ->addClass('progress');

        // Append progress bar elements
        foreach ($this->bars as $options) {
            $options = array_merge($defaults, $options);

            $progress->appendChild($this->createSingleProgress(
                $options['value'], 
                $options['label'], 
                $options['striped'], 
                $options['animated'], 
                $options['background'])
            );
        }

        return $progress->render();
    }

    /**
     * Renders progress
     * 
     * @param int $value Current value
     * @param boolean $label Whether to place label
     * @param boolean $striped
     * @param boolean $animated
     * @param string $background Optional background class
     * @return \Krystal\Form\NodeElement
     */
    private function createSingleProgress($value, $label, $striped, $animated, $background)
    {
        // General CSS class
        $progressClass = 'progress-bar';

        // Must be striped or not?
        if ($striped == true) {
            $progressClass .= ' progress-bar-striped';
        }

        // Must be animated or not?
        if ($animated == true) {
            $progressClass .= ' progress-bar-animated';
        }

        // Do we need to append background class?
        if ($background) {
            $progressClass .= ' ' . $background;
        }

        // Bar attributes
        $attributes = [
            'class' => $progressClass,
            'role' => 'progressbar',
            'style' => 'width: ' . $value . '%',
            'aria-valuenow' => $value,
            'aria-valuemin' => '0',
            'aria-valuemax' => '100'
        ];

        $bar = new NodeElement();
        $bar->openTag('div')
            ->addAttributes($attributes);

        if ($label) {
            $bar->setText($value . '%');
        } else {
            $bar->finalize();
        }

        $bar->closeTag();

        return $bar;
    }
}

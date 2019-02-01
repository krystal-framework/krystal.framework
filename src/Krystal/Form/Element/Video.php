<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Form\Element;

use Krystal\Form\NodeElement;

final class Video extends AbstractMediaElement implements FormElementInterface
{
    /**
     * {@inheritDoc}
     */
    protected $error = 'Your browser does not support the video element';

    /**
     * Renders video element
     * 
     * @param array $sources
     * @param boolean $autoplay Whether autoplay is required
     * @param int $width Optional width
     * @param int $height Optional height
     * @return string
     */
    private function createVideo(array $sources, $autoplay, $width, $height)
    {
        $node = new NodeElement();

        $node->openTag('video')
             ->addProperty('controls');

        if ($autoplay === true) {
            $node->addProperty('autoplay');
        }

        if ($width !== null) {
            $node->addAttribute('width', $width);
        }

        if ($height !== null) {
            $node->addAttribute('height', $height);
        }

        return $node->finalize(false)
                    ->appendChildren($sources)
                    ->setText($this->error)
                    ->closeTag()
                    ->render();
    }

    /**
     * {@inheritDoc}
     */
    public function render(array $attrs)
    {
        $sources = $this->createSourceElements($this->sources);

        return $this->createVideo($sources, $attrs['autoplay'], $attrs['width'], $attrs['height']);
    }
}

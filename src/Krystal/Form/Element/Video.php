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
     * @param boolean $loop Specifies that the video will start over again, every time it is finished
     * @param boolean $muted Specifies that the audio output of the video should be muted
     * @param int $width Optional width
     * @param int $height Optional height
     * @param string $poster Specifies an image to be shown while the video is downloading, or until the user hits the play button
     * @return string
     */
    private function createVideo(array $sources, $autoplay, $loop, $muted, $width, $height, $poster)
    {
        $node = new NodeElement();

        $node->openTag('video')
             ->addProperty('controls');

        if ($autoplay === true) {
            $node->addProperty('autoplay');
        }

        if ($loop === true) {
            $node->addProperty('loop');
        }

        if ($muted === true) {
            $node->addProperty('muted');
        }

        if ($width !== null) {
            $node->addAttribute('width', $width);
        }

        if ($height !== null) {
            $node->addAttribute('height', $height);
        }

        if ($poster !== null) {
            $node->addAttribute('poster', $poster);
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

        return $this->createVideo($sources, $attrs['autoplay'], $attrs['loop'], $attrs['muted'], $attrs['width'], $attrs['height'], $attrs['poster']);
    }
}

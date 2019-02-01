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

final class Video implements FormElementInterface
{
    /**
     * Sources with their MIME-types
     * 
     * @var array
     */
    private $sources;

    /**
     * Error message in case the element is not supported
     * 
     * @var string
     */
    private $error = 'Your browser does not support the video element';

    /**
     * State initialization
     * 
     * @param array $sources
     * @param string $error Error message
     * @return void
     */
    public function __construct(array $sources, $error = null)
    {
        $this->sources = $sources;

        // Override on demand
        if ($error !== null) {
            $this->error = $error;
        }
    }

    /**
     * Create source node elements
     * 
     * @param array $sources
     * @return array
     */
    private function createSourceElements(array $sources)
    {
        // To be returned
        $output = array();

        foreach ($sources as $type => $src) {
            $output[] = $this->createSourceElement($type, $src);
        }

        return $output;
    }

    /**
     * Create inner source node element
     * 
     * @param string $type MIME-type
     * @param string $src Path to audio file
     * @return \Krystal\Form\NodeElement
     */
    private function createSourceElement($type, $src)
    {
        // Tag attributes
        $attrs = array(
            'src' => $src,
            'type' => $type
        );

        $node = new NodeElement();

        return $node->openTag('source')
                    ->addAttributes($attrs)
                    ->finalize(true);
    }

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

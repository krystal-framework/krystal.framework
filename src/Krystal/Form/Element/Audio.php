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

final class Audio extends AbstractMediaElement implements FormElementInterface
{
    /**
     * {@inheritDoc}
     */
    protected $error = 'Your browser does not support the audio element';

    /**
     * Renders audio element
     * 
     * @param array $sources
     * @return string
     */
    private function renderAudio(array $sources)
    {
        $node = new NodeElement();

        return $node->openTag('audio')
                    ->addProperty('controls')
                    ->finalize(false)
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

        return $this->renderAudio($sources);
    }
}

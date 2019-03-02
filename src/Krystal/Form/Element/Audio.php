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

final class Audio extends AbstractMediaElement
{
    /**
     * {@inheritDoc}
     */
    protected $error = 'Your browser does not support the audio element';

    /**
     * {@inheritDoc}
     */
    protected function renderElement($src, array $attrs)
    {
        $node = new NodeElement();

        $node->openTag('audio')
             ->addAttributes($attrs);

        // If multi paths provided
        if (is_array($src)) {
            $node->finalize(false)
                 ->appendChildren($src);
        } else {
            // Single path assumed
            $node->addAttribute('src', $src)
                 ->finalize(false);
        }

        $node->setText($this->error)
             ->closeTag();

        return $node->render();
    }
}

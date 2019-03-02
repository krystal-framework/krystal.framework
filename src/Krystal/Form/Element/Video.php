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
use UnexpectedValueException;

final class Video extends AbstractMediaElement
{
    /**
     * {@inheritDoc}
     */
    protected $error = 'Your browser does not support the video element';

    /**
     * {@inheritDoc}
     */
    protected function renderElement($src, array $attrs)
    {
        $node = new NodeElement();

        $node->openTag('video')
             ->addProperty('controls');

        if (isset($attrs['autoplay']) && $attrs['autoplay'] == true) {
            $node->addProperty('autoplay');
        }

        if (isset($attrs['loop']) && $attrs['loop'] == true) {
            $node->addProperty('loop');
        }

        if (isset($attrs['muted']) && $attrs['muted'] == true) {
            $node->addProperty('muted');
        }

        if (isset($attrs['width'])) {
            $node->addAttribute('width', $attrs['width']);
        }

        if (isset($attrs['height'])) {
            $node->addAttribute('height', $attrs['height']);
        }

        if (isset($attrs['poster'])) {
            $node->addAttribute('poster', $attrs['poster']);
        }

        if (isset($attrs['preload'])) {
            // Make sure only valid that only one of valid values supplied
            if (in_array($attrs['preload'], array('auto', 'metadata', 'none'))) {
                $node->addAttribute('preload', $attrs['preload']);
            } else {
                throw new UnexpectedValueException('Unexpected value for "preload" attribute supplied');
            }
        }

        // If multi paths provided
        if (is_array($src)) {
            $node->finalize(false)
                 ->appendChildren($src);
        } else {
            // Single path assumed
            $node->addAttribute('src', $src);
        }

        $node->setText($this->error)
             ->closeTag();

        return $node->render();
    }
}

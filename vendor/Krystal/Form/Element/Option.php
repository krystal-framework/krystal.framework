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

final class Option implements FormElementInterface
{
    /**
     * Text between tags
     * 
     * @var string
     */
    private $text;

    /**
     * State initialization
     * 
     * @param string $text
     * @return void
     */
    public function __construct($text)
    {
        $this->text = $text;
    }

    /**
     * {@inheritDoc}
     */
    public function render(array $attrs)
    {
        $node = new NodeElement();

        return $node->openTag('option')
                    ->addAttributes($attrs)
                    ->finalize(false)
                    ->setText($this->text)
                    ->closeTag()
                    ->render();
    }
}

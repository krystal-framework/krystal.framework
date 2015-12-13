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

final class Link implements FormElementInterface
{
    /**
     * Target text
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

        return $node->openTag('a')
                    ->addAttributes($attrs)
                    ->finalize(false)
                    ->setText($this->text)
                    ->closeTag()
                    ->render();
    }
}

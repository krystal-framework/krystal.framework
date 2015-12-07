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

final class Label implements FormElementInterface
{
    /**
     * Label's caption
     * 
     * @var string
     */
    private $caption;

    /**
     * State initialization
     * 
     * @param string $caption
     * @return void
     */
    public function __construct($caption)
    {
        $this->caption = $caption;
    }

    /**
     * {@inheritDoc}
     */
    public function render(array $attrs)
    {
        $node = new NodeElement();
        
        return $node->openTag('label')
                    ->addAttributes($attrs)
                    ->finalize(false)
                    ->setText($this->caption)
                    ->closeTag()
                    ->render();
    }
}

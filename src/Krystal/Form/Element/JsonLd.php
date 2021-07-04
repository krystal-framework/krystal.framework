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

final class JsonLd implements FormElementInterface
{
    /**
     * Data array
     * 
     * @var array
     */
    private $data;

    /**
     * State initialization
     * 
     * @param array $data
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * {@inheritDoc}
     */
    public function render(array $attrs)
    {
        $node = new NodeElement();

        return $node->openTag('script')
                    ->addAttribute('type', 'application/ld+json')
                    ->finalize(false)
                    ->setText(json_encode($this->data))
                    ->closeTag()
                    ->render();
    }
}

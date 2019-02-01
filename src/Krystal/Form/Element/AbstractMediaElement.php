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

abstract class AbstractMediaElement
{
    /**
     * Sources with their MIME-types
     * 
     * @var array
     */
    protected $sources;

    /**
     * Error message in case the element is not supported
     * 
     * @var string
     */
    protected $error;

    /**
     * State initialization
     * 
     * @param array $sources
     * @param string $error Error message
     * @return void
     */
    final public function __construct(array $sources, $error = null)
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
    final protected function createSourceElements(array $sources)
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
    final protected function createSourceElement($type, $src)
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
}

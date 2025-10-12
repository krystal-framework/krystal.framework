<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Form\Element;

use Krystal\Form\NodeElement;

abstract class AbstractMediaElement implements FormElementInterface
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
     * @param array|string $sources
     * @param string $error Error message
     * @return void
     */
    final public function __construct($sources, $error = null)
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

    /**
     * Renders media element
     * 
     * @param array|string $src File path or collection of file paths
     * @param array $attrs Element attributes
     * @return string
     */
    abstract protected function renderElement($src, array $attrs);

    /**
     * {@inheritDoc}
     */
    public function render(array $attrs)
    {
        $src = is_array($this->sources) ? $this->createSourceElements($this->sources) : $this->sources;

        return $this->renderElement($src, $attrs);
    }
}

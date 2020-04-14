<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Seo\Sitemap;

use DOMDocument;

abstract class AbstractGenerator
{
    /**
     * Root document instance
     * 
     * @var \DOMDocument
     */
    protected $document;

    /**
     * URL DOMElements to be rendered
     * 
     * @var array
     */
    protected $items = array();

    /**
     * State initialization
     * 
     * @param string $encoding Document encoding
     * @return void
     */
    public function __construct($encoding = 'UTF-8')
    {
        $this->document = new DOMDocument('1.0', $encoding);
        $this->document->formatOutput = true;
    }

    /**
     * Generates and renders Sitemap as a string
     * 
     * @return string
     */
    abstract public function render();

    /**
     * Creates child element
     * 
     * @param string $tagName
     * @param string $value Element value
     * @param array $attributes Element attributes
     * @return \DOMElement
     */
    final protected function createNode($tagName, $value = null, array $attributes = array())
    {
        $element = $this->document->createElement($tagName);

        // Set value if provided
        if ($value !== null) {
            $element->nodeValue = $value;
        }

        // Set attributes, if provided
        foreach ($attributes as $key => $value) {
            $element->setAttribute($key, $value);
        }

        return $element;
    }

    /**
     * Creates root element with its children
     * 
     * @param string $root Root element tag name
     * @param array $branches
     * @return \DOMElement
     */
    final protected function createBranch($root, array $branches)
    {
        // Root element
        $rootNode = $this->createNode($root);

        // Append items to URL set
        foreach ($branches as $tagName => $tagValue) {
            $childNode = $this->createNode($tagName, $tagValue);

            // Append only non-empty values
            if ($childNode->nodeValue) {
                $rootNode->appendChild($childNode);
            }
        }

        return $rootNode;
    }
}

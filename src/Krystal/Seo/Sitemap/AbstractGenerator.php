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
use DateTime;
use Exception;

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
     * Whether to validate supplied values
     * 
     * @var boolean
     */
    protected $validate;

    /**
     * State initialization
     * 
     * @param boolean $validate Whether to validate supplied values
     * @param string $encoding Document encoding
     * @return void
     */
    public function __construct($validate = true, $encoding = 'UTF-8')
    {
        $this->validate = $validate;

        $this->document = new DOMDocument('1.0', $encoding);
        $this->document->formatOutput = true;
    }

    /**
     * Properly formats lastmod value
     * 
     * @param string $lastmod
     * @return mixed
     */
    protected static function formatLastmod($lastmod)
    {
        // Do we even need to process this one?
        if ($lastmod == null) {
            return $lastmod;
        }

        try {
            $datetime = new DateTime($lastmod);
            return $datetime->format('Y-m-d\TH:i:sP');
        } catch (Exception $e){
            return null;
        }
    }

    /**
     * Safely return a value from array
     * 
     * @param array $row
     * @param string $key
     * @return mixed
     */
    protected static function safeValue(array $row, $key)
    {
        return isset($row[$key]) ? $row[$key] : null;
    }

    /**
     * Generates and renders Sitemap as a string
     * 
     * @return string
     */
    abstract public function render();

    /**
     * Renders XML document
     * 
     * @param string $tagName Root element name
     * @return string
     */
    final protected function createTree($tagName)
    {
        $rootNode = $this->createNode($tagName, null, $this->rootAttributes);

        foreach ($this->items as $child) {
            $rootNode->appendChild($child);
        }

        $this->document->appendChild($rootNode);

        return $this->document->saveXML();
    }
    
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

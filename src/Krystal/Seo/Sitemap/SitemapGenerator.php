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

final class SitemapGenerator
{
    /**
     * Root document instance
     * 
     * @var \DOMDocument
     */
    private $document;

    /**
     * URL DOMElements to be rendered
     * 
     * @var array
     */
    private $items = array();

    /**
     * Default URLset attributes (for root element)
     * 
     * @var array
     */
    private $urlSetAttributes = array(
        'xmlns' => 'http://www.sitemaps.org/schemas/sitemap/0.9',
        'xmlns:image' => 'http://www.google.com/schemas/sitemap-image/1.1',
        'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance'
    );

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
    public function render()
    {
        $urlset = $this->createNode('urlset', null, $this->urlSetAttributes);

        foreach ($this->items as $url) {
            $urlset->appendChild($url);
        }

        $this->document->appendChild($urlset);

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
    private function createNode($tagName, $value = null, array $attributes = array())
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
    private function createBranch($root, array $branches)
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

    /**
     * Add many URLs
     * 
     * @param array $urls
     * @return void
     */
    public function addUrls(array $urls)
    {
        // Returns key from array
        $get = function($key, $row){
            return isset($row[$key]) ? $row[$key] : null;
        };

        foreach ($urls as $url) {
            $this->addUrl(
                $get('loc', $url),
                $get('lastmod', $url),
                $get('changefreq', $url),
                $get('priority', $url)
            );
        }
    }

    /**
     * Creates URL element
     * 
     * @param string $loc URL of the page.
     * @param string $lastmod The date of last modification of the file. This date should be in W3C Datetime format.
     * @param string $changefreq How frequently the page is likely to change.
     * @param string $priority The priority of this URL relative to other URLs on your site. Valid values range from 0.0 to 1.0.
     * @return void
     */
    public function addUrl($loc, $lastmod = null, $changefreq = null, $priority = null)
    {
        $node = $this->createBranch('url', array(
            'loc' => $loc,
            'lastmod' => $lastmod,
            'changefreq' => $changefreq,
            'priority' => $priority
        ));

        $this->items[] = $node;
    }
}

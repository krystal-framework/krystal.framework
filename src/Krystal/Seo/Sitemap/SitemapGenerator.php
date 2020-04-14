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
    private $urls = array();
    
    /**
     * State initialization
     * 
     * @param string $encoding Document encoding
     * @return void
     */
    public function __construct($encoding = 'UTF-8')
    {
        $this->document = new DOMDocument('1.0', $encoding);
    }

    /**
     * Generates and renders Sitemap as a string
     * 
     * @return string
     */
    public function render()
    {
        $this->document->formatOutput = true;

        $urlset = $this->document->createElement('urlset');
        $urlset->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $urlset->setAttribute('xmlns:image', 'http://www.google.com/schemas/sitemap-image/1.1');
        $urlset->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');

        foreach ($this->urls as $url) {
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
     * @return \DOMElement
     */
    private function createNode($tagName, $value)
    {
        $element = $this->document->createElement($tagName);
        $element->nodeValue = $value;

        return $element;
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
        // Root element
        $urlElement = $this->document->createElement('url');

        $elements = array(
            $this->createNode('loc', $loc),
            $this->createNode('lastmod', $lastmod),
            $this->createNode('changefreq', $changefreq),
            $this->createNode('priority', $priority)
        );

        // Append items to URL set
        foreach ($elements as $element) {
            // Append only non-empty values
            if ($element->nodeValue) {
                $urlElement->appendChild($element);
            }
        }

        $this->urls[] = $urlElement;
    }
}

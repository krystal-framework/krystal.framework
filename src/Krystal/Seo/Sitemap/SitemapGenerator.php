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

        $locElement = $this->document->createElement('loc');
        $locElement->nodeValue = $loc;

        $lastmodElement = $this->document->createElement('lastmod');
        $lastmodElement->nodeValue = $lastmod;

        $changefreqElement = $this->document->createElement('changefreq');
        $changefreqElement->nodeValue = $changefreq;

        $priorityElement = $this->document->createElement('priority');
        $priorityElement->nodeValue = $priority;

        // Append items to URL set
        foreach (array($locElement, $lastmodElement, $priorityElement, $changefreqElement) as $item) {
            // Append only non-empty values
            if ($item->nodeValue) {
                $urlElement->appendChild($item);
            }
        }

        $this->urls[] = $urlElement;
    }
}

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Seo\Sitemap\Google;

use Krystal\Seo\Sitemap\AbstractGenerator;

final class VideoSitemap extends AbstractGenerator
{
    /**
     * {@inheritDoc}
     */
    protected $rootAttributes = array(
        'xmlns' => 'http://www.sitemaps.org/schemas/sitemap/0.9',
        'xmlns:video' => 'http://www.google.com/schemas/sitemap-video/1.1'
    );

    /**
     * {@inheritDoc}
     */
    public function render()
    {
        return $this->createTree('urlset');
    }

    /**
     * Creates a node or returns null
     * 
     * @param string $tagName
     * @param string|array $value
     * @return mixed
     */
    private function createItem($param, $value)
    {
        $tagName = sprintf('video:%s', $param);

        if (is_array($value)) {
            $target = $value[$param];
            unset($value[$param]);
            $node = $this->createNode($tagName, $target, $value);
        } else {
            $node = $this->createNode($tagName, $value);
        }

        return $node;
    }

    /**
     * Create video items from params
     * 
     * @param array $params
     * @return \DOMElement
     */
    private function createFromParams(array $params)
    {
        $videoNodes = array();

        // Turn parameters into video nodes
        foreach ($params as $key => $value) {
            $videoNodes[] = $this->createItem($key, $value);
        }

        return $videoNodes;
    }

    /**
     * Add many URLs at once
     * 
     * @param array $urls
     * @return \Krystal\Seo\Sitemap\Google\VideoSitemap
     */
    public function addUrls(array $urls)
    {
        foreach ($urls as $url) {
            $this->addUrl($url['loc'], $url['params']);
        }

        return $this;
    }

    /**
     * Adds new URL entry
     * 
     * @param string $loc
     * @return \Krystal\Seo\Sitemap\Google\VideoSitemap
     */
    public function addUrl($loc, array $params)
    {
        $urlNode = $this->createNode('url');

        $loc = $this->createNode('loc', $loc);
        $urlNode->appendChild($loc);

        // Append video items
        $videoParentNode = $this->createNode('video:video');

        foreach ($this->createFromParams($params) as $videoNode) {
            $videoParentNode->appendChild($videoNode);
        }

        $urlNode->appendChild($videoParentNode);

        $this->items[] = $urlNode;
        return $this;
    }
}

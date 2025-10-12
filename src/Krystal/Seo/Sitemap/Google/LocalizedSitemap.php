<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Seo\Sitemap\Google;

use Krystal\Seo\Sitemap\AbstractGenerator;

final class LocalizedSitemap extends AbstractGenerator
{
    /**
     * {@inheritDoc}
     */
    protected $rootAttributes = array(
        'xmlns' => 'http://www.sitemaps.org/schemas/sitemap/0.9',
        'xmlns:xhtml' => 'http://www.w3.org/1999/xhtml'
    );

    /**
     * {@inheritDoc}
     */
    public function render()
    {
        return $this->createTree('urlset');
    }

    /**
     * Creates locale node
     * 
     * @param string $hreflang
     * @param string $href
     * @return \DOMElement
     */
    private function createLocaleNode($hreflang, $href)
    {
        return $this->createNode('xhtml:link', null, array(
            'rel' => 'alternate',
            'hreflang' => $hreflang,
            'href' => $href
        ));
    }

    /**
     * Adds many URLs at once
     * 
     * @param array $urls
     * @return \Krystal\Seo\Sitemap\Google\LocalizedSitemap
     */
    public function addUrls(array $urls)
    {
        foreach ($urls as $url) {
            $this->addUrl($url['hreflang'], $url['href'], $url['translations']);
        }

        return $this;
    }

    /**
     * Adds new URL
     * 
     * @param string $hreflang Primary locale code
     * @param string $href Primary URL
     * @param array $translations
     * @return \Krystal\Seo\Sitemap\Google\LocalizedSitemap
     */
    public function addUrl($hreflang, $href, array $translations)
    {
        $urlNode = $this->createNode('url');

        $locNode = $this->createNode('loc', $href);
        $urlNode->appendChild($locNode);

        foreach ($translations as $key => $value) {
            $internalNode = $this->createLocaleNode($key, $value);
            $urlNode->appendChild($internalNode);
        }

        // Append self
        $baseNode = $this->createLocaleNode($hreflang, $href);
        $urlNode->appendChild($baseNode);

        $this->items[] = $urlNode;
        return $this;
    }
}

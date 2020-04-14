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

final class SitemapIndexGenerator extends AbstractGenerator
{
    /**
     * Root attributes
     * 
     * @var array
     */
    private $attributes = array(
        'xmlns' => 'http://www.sitemaps.org/schemas/sitemap/0.9'
    );

    /**
     * {@inheritDoc}
     */
    public function render()
    {
        $sitemapindex = $this->createNode('sitemapindex', null, $this->attributes);

        foreach ($this->items as $item) {
            $sitemapindex->appendChild($item);
        }

        $this->document->appendChild($sitemapindex);

        return $this->document->saveXML();
    }
    
    /**
     * Add single Sitemap
     * 
     * @param array $sitemaps
     * @return void
     */
    public function addSitemaps(array $sitemaps)
    {
        // Returns key from array
        $get = function($key, $row){
            return isset($row[$key]) ? $row[$key] : null;
        };

        foreach ($urls as $url) {
            $this->addUrl(
                $get('loc', $url),
                $get('lastmod', $url)
            );
        }
    }

    /**
     * Add single Sitemap
     * 
     * @param string $loc
     * @param string $loc
     * @return void
     */
    public function addSitemap($loc, $lastmod = null)
    {
        $node = $this->createBranch('sitemap', array(
            'loc' => $loc,
            'lastmod' => $lastmod
        ));

        $this->items[] = $node;
    }
}

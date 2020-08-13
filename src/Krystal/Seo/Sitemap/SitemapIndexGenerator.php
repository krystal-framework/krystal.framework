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
    protected $rootAttributes = array(
        'xmlns' => 'http://www.sitemaps.org/schemas/sitemap/0.9'
    );

    /**
     * {@inheritDoc}
     */
    public function render()
    {
        return $this->createTree('sitemapindex');
    }

    /**
     * Add single Sitemap
     * 
     * @param array $sitemaps
     * @return \Krystal\Seo\Sitemap\SitemapIndexGenerator
     */
    public function addSitemaps(array $sitemaps)
    {
        foreach ($sitemaps as $sitemap) {
            $this->addSitemap(
                self::safeValue('loc', $sitemap),
                self::safeValue('lastmod', $sitemap)
            );
        }

        return $this;
    }

    /**
     * Add single Sitemap
     * 
     * @param string $loc
     * @param string $lastmod
     * @return \Krystal\Seo\Sitemap\SitemapIndexGenerator
     */
    public function addSitemap($loc, $lastmod = null)
    {
        if ($this->validate == true) {
            // Validate loc
            if (!Validator::isLoc($loc)) {
                Validator::throwError('loc', $loc);
            }
        }

        $node = $this->createBranch('sitemap', array(
            'loc' => self::escapeUrl($loc),
            'lastmod' => self::formatLastmod($lastmod)
        ));

        $this->items[] = $node;
        return $this;
    }
}

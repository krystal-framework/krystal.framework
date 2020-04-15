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

final class SitemapGenerator extends AbstractGenerator
{
    /**
     * {@inheritDoc}
     */
    protected $rootAttributes = array(
        'xmlns' => 'http://www.sitemaps.org/schemas/sitemap/0.9',
        'xmlns:image' => 'http://www.google.com/schemas/sitemap-image/1.1',
        'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance'
    );

    /**
     * {@inheritDoc}
     */
    public function render()
    {
        return $this->createTree('urlset');
    }

    /**
     * Add many URLs
     * 
     * @param array $urls
     * @return void
     */
    public function addUrls(array $urls)
    {
        foreach ($urls as $url) {
            $this->addUrl(
                self::safeValue('loc', $url),
                self::safeValue('lastmod', $url),
                self::safeValue('changefreq', $url),
                self::safeValue('priority', $url)
            );
        }
    }

    /**
     * Add single URL
     * 
     * @param string $loc URL of the page.
     * @param string $lastmod The date of last modification of the file. This date should be in W3C Datetime format.
     * @param string $changefreq How frequently the page is likely to change.
     * @param string $priority The priority of this URL relative to other URLs on your site. Valid values range from 0.0 to 1.0.
     * @return void
     */
    public function addUrl($loc, $lastmod = null, $changefreq = null, $priority = null)
    {
        // Do we need to run validation?
        if ($this->validate == true) {
            // Validate loc
            if (!Validator::isLoc($loc)) {
                Validator::throwError('loc', $loc);
            }

            // Validate changefreq
            if ($changefreq !== null && !Validator::isChangefreq($changefreq)) {
                Validator::throwError('changefreq', $changefreq);
            }
            
            // Validate priority
            if ($priority !== null && !Validator::isPriority($priority)) {
                Validator::throwError('priority', $priority);
            }
        }

        $node = $this->createBranch('url', array(
            'loc' => $loc,
            'lastmod' => self::formatLastmod($lastmod),
            'changefreq' => $changefreq,
            'priority' => $priority
        ));

        $this->items[] = $node;
    }
}

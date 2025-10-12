<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Seo\Sitemap;

final class SitemapGenerator extends AbstractGenerator
{
    /* Available frequencies */
    const FREQ_ALWAYS = 'always';
    const FREQ_HOURLY = 'hourly';
    const FREQ_DAILY = 'daily';
    const FREQ_WEEKLY = 'weekly';
    const FREQ_MONTHLY = 'monthly';
    const FREQ_YEARLY = 'yearly';
    const FREQ_NEVER = 'never';

    /**
     * {@inheritDoc}
     */
    protected $rootAttributes = array(
        'xmlns' => 'http://www.sitemaps.org/schemas/sitemap/0.9',
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
     * @return \Krystal\Seo\Sitemap\SitemapGenerator
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

        return $this;
    }

    /**
     * Add single URL
     * 
     * @param string $loc URL of the page.
     * @param string $lastmod The date of last modification of the file. This date should be in W3C Datetime format.
     * @param string $changefreq How frequently the page is likely to change.
     * @param string $priority The priority of this URL relative to other URLs on your site. Valid values range from 0.0 to 1.0.
     * @throws \UnexpectedValueException On invalid value when encountered
     * @return \Krystal\Seo\Sitemap\SitemapGenerator
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
            'loc' => self::escapeUrl($loc),
            'lastmod' => self::formatLastmod($lastmod),
            'changefreq' => $changefreq,
            'priority' => $priority
        ));

        $this->items[] = $node;
        return $this;
    }
}

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Seo\Sitemap\Google;

use Krystal\Seo\Sitemap\AbstractGenerator;

final class NewsSitemap extends AbstractGenerator
{
    /**
     * {@inheritDoc}
     */
    protected $rootAttributes = array(
        'xmlns' => 'http://www.sitemaps.org/schemas/sitemap/0.9',
        'xmlns:news' => 'http://www.google.com/schemas/sitemap-news/0.9'
    );

    /**
     * {@inheritDoc}
     */
    public function render()
    {
        return $this->createTree('urlset');
    }

    /**
     * Add many URLs at once
     * 
     * @param array $urls
     * @return \Krystal\Seo\Sitemap\Google\NewsSitemap
     */
    public function addUrls(array $urls)
    {
        foreach ($urls as $url) {
            $this->addUrl(
                $url['loc'],
                $url['name'],
                $url['locale'],
                $url['date'],
                isset($url['title']) ? $url['title'] : null
            );
        }

        return $this;
    }

    /**
     * Adds new URL
     * 
     * @param string $loc URL of the page.
     * @param string $name Post name
     * @param string $locale Post locale
     * @param string $date Article publication date in W3C format
     * @param string $title Post title
     * @return \Krystal\Seo\Sitemap\Google\NewsSitemap
     */
    public function addUrl($loc, $name, $locale, $date, $title = null)
    {
        // If title is empty, replace it with name
        if (empty($title)) {
            $title = $name;
        }

        $node = $this->createBranch('url', array(
            'loc' => self::escapeUrl($loc),
            'news:news' => array(
                'news:publication' => array(
                    'news:name' => $name,
                    'news:language' => $locale
                ),
                'news:publication_date' => $date,
                'news:title' => $title
            )
        ));

        $this->items[] = $node;
        return $this;
    }
}

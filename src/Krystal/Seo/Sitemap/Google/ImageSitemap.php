<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Seo\Sitemap\Google;

use Krystal\Seo\Sitemap\AbstractGenerator;

final class ImageSitemap extends AbstractGenerator
{
    /**
     * {@inheritDoc}
     */
    protected $rootAttributes = array(
        'xmlns' => 'http://www.sitemaps.org/schemas/sitemap/0.9',
        'xmlns:image' => 'http://www.google.com/schemas/sitemap-image/1.1'
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
     * @return \Krystal\Seo\Sitemap\Google\ImageSitemap
     */
    public function addUrls(array $urls)
    {
        foreach ($urls as $url) {
            $this->addUrl($url['loc'], $url['images']);
        }

        return $this;
    }

    /**
     * Adds a single entry
     * 
     * @param string $loc Target URL
     * @param array $images Images
     * @return \Krystal\Seo\Sitemap\Google\ImageSitemap
     */
    public function addUrl($loc, array $images)
    {
        $url = $this->createNode('url');

        foreach ($images as $image) {
            // Internal image
            $node = $this->createBranch('image:image', array(
                'image:loc' => $image['loc'], // Required
                'image:caption' => self::safeValue('caption', $image), // Optional
                'image:geo_location' => self::safeValue('geo_location', $image),  // Optional
                'image:title' => self::safeValue('title', $image),  // Optional
                'image:license' => self::safeValue('license', $image)  // Optional
            ));

            $url->appendChild($this->createNode('loc', $loc));
            $url->appendChild($node);
        }

        $this->items[] = $url;
        return $this;
    }
}

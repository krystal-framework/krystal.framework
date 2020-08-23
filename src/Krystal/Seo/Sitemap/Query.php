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

final class Query
{
    /**
     * Sitemap URL
     * 
     * @var string
     */
    private $url;

    /**
     * Search engines that accept ping URLs
     * 
     * @var array
     */
    private $engines = array(
        'http://www.google.com/webmasters/sitemaps/ping?sitemap=%s',
        'http://www.bing.com/ping?sitemap=%s',
        'http://blogs.yandex.ru/pings/?status=success&url=%s'
    );

    /**
     * State initialization
     * 
     * @param string $url Front SiteMap URL
     * @return void
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * Inform search engines about SiteMap location
     * 
     * @return boolean
     */
    public function ping()
    {
        $hasError = false;

        foreach ($this->engines as $engine) {
            $target = sprintf($engine, urlencode($this->url));

            // Issue a GET request
            $hasError = @file_get_contents($target) !== false;
        }

        return $hasError;
    }
}

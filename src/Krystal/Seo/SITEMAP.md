Sitemap generator
===========

A sitemap is a file that lists the pages, images, videos, or other content on your website and provides metadata about them. It helps search engines understand your site’s structure so they can crawl it more efficiently.
With this component, creating sitemaps is fast, straightforward, and hassle-free.

Key features:

 * Fully compliant with the official sitemap protocol
 * Optional value validation (enabled by default)
 * Automatic conversion of most date formats to W3C Datetime format for the lastmod field
 * Automatic escaping of HTML characters in URLs

### Single sitemap

Use this option when your site has only one sitemap.

    <?php
    
    use Krystal\Seo\Sitemap\SitemapGenerator;
    
    $generator = new SitemapGenerator();
    
    $generator->addUrl('http://domain.com/our-story', '2020-11-23 00:01:03', 'daily', '1.0');
    $generator->addUrl('http://domain.com/team', '2020-11-25 00:01:03', 'weekly', '0.8');
    $generator->addUrl('http://domain.com/news', '2020-11-22 00:01:03', 'weekly', '0.8');
    
    echo $generator->render();

This outputs the following:

    <?xml version="1.0" encoding="UTF-8"?>
    <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
      <url>
        <loc>http://domain.com/our-story</loc>
        <lastmod>2020-11-23T00:01:03+00:00</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
      </url>
      <url>
        <loc>http://domain.com/team</loc>
        <lastmod>2020-11-25T00:01:03+00:00</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
      </url>
      <url>
        <loc>http://domain.com/news</loc>
        <lastmod>2020-11-22T00:01:03+00:00</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
      </url>
    </urlset>

Alternatively, when adding URLs, you can also provide an array of entires, like this:

    $generator->addUrls([
        [
            'loc' => 'http://domain.com/our-story',
            'lastmod' => '2020-11-23 00:01:03',
            'changefreq' => 'daily',
            'priority' => '1.0'
        ],

        [
            'loc' => 'http://domain.com/team',
            'lastmod' => '2020-11-25 00:01:03',
            'changefreq' => 'weekly',
            'priority' => '0.8'
        ],

        [
            'loc' => 'http://domain.com/news',
            'lastmod' => '2020-11-22 00:01:03',
            'changefreq' => 'weekly',
            'priority' => '0.8'
        ]
    ]);

These constants can also be used when providing the $changefreq argument.

    SitemapGenerator::FREQ_ALWAYS
    SitemapGenerator::FREQ_HOURLY
    SitemapGenerator::FREQ_DAILY
    SitemapGenerator::FREQ_WEEKLY
    SitemapGenerator::FREQ_MONTHLY
    SitemapGenerator::FREQ_YEARLY
    SitemapGenerator::FREQ_NEVER

Like this:

    $generator->addUrl('http://domain.com/our-story', '2020-11-23 00:01:03', SitemapGenerator::FREQ_WEEKLY);


## Multiple sitemaps

You can also generate a grouped sitemap that references other sitemaps. Here’s how:
    
    <?php
    
    use Krystal\Seo\Sitemap\SitemapIndexGenerator;
    
    $generator = new SitemapIndexGenerator();
    
    $generator->addSitemap('http://domain.com/sitemaps/blog.xml');
    $generator->addSitemap('http://domain.com/sitemaps/shop.xml', '2020-11-25 00:01:03');
    
    echo $generator->render();

This outputs the following:

    <?xml version="1.0" encoding="UTF-8"?>
    <sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
      <sitemap>
        <loc>http://domain.com/sitemaps/blog.xml</loc>
      </sitemap>
      <sitemap>
        <loc>http://domain.com/sitemaps/shop.xml</loc>
        <lastmod>2020-11-25T00:01:03+00:00</lastmod>
      </sitemap>
    </sitemapindex>

Alternatively, when adding sitemaps, you can provide an array of entries, like this:

    $generator->addSitemaps([
        [
            'loc' => 'http://domain.com/sitemaps/blog.xml',
        ],
    
        [
            'loc' => 'http://domain.com/sitemaps/shop.xml',
            'lastmod' => '2020-11-25 00:01:03'
        ]
    ]);


## Notes

By default, all supplied values are validated. To disable validation, pass false to the constructor, for example: `$generator = new SitemapIndexGenerator(false)` or `$generator = new SitemapGenerator(false)`.
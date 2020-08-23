SEO component
=============

This component provides tools related to SEO


## Robots

Using this tool, you can generate content for the `robots.txt` file easily. Quick example:

    <?php
    
    use Krystal\Seo\Robots;
    
    $robots = new Robots();
    $robots->addComment('Default generated robots.txt')
           ->addUserAgent('*')
           ->addDisallow([
                '/config/',
                '/modules/'
           ])
           ->addAllow([
                '/images/'
           ])
           ->addBreak()
           ->addHost('domain.com')
           ->addBreak()
           ->addSitemap([
                'https://domain.com/sitemap-1.xml',
                'https://domain.com/sitemap-2.xml'
           ]);
    
    echo $robots->render();

This outputs the following:

    # Default generated robots.txt
    User-agent: *
    Disallow: /config/
    Disallow: /modules/
    Allow: /images/
    
    Host: domain.com
    
    Sitemap: https://domain.com/sitemap-1.xml
    Sitemap: https://domain.com/sitemap-2.xml

Methods such as `addUserAgent()`, `addDisallow()`, `addAllow()` and `addSitemap()` accept an array of values or a single value.

You can also save generated robots file into a directory. For that use `save($dir)` method, where `$dir` is a path to directory where file should be saved.

# Sitemap

You can use this component to generate sitemaps. It's real fast and super easy to use! Key features:

 * Implementation according to https://www.sitemaps.org/protocol.html
 * Optional value validation (By default enabled)
 * Automatically convert most date formats into [W3C Datetime format](https://www.w3.org/TR/NOTE-datetime) (in lastmod)
 * Automatically escape HTML characters in URLs

## Single sitemap

This one is typically used if your site has only one sitemap (or you group all of your URLs into a one).

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

You can also use these constants when supplying third argument `$changefreq`

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

You can also generate a grouped sitemap that points to another sitemaps. It goes like this:
    
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

Alternatively, when adding sitemaps, you can also provide an array of entires, like this:

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

By default, it checks wheter all supplied values are valid. In case you need to disable validation, just pass `false` to constructor, like this `$generator = new SitemapIndexGenerator(false)` or `$generator = new SitemapGenerator(false)`


## Submit Sitemap to Search Engines

You can easily submit your sitemap using `Query` class, like this:

    <?php
    
    use Krystal\Seo\Sitemap\Query;
    
    $query = new Query('http://example.com/sitemap.xml');
    $query->ping(); // Submit your sitemap to all major search engines
    
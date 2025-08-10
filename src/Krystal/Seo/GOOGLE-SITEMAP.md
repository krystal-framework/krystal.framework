Google-specific Sitemap generators
==========

This document explains how to use specific implementations of Google Sitemaps.

### Image sitemap

Google provides a special type of Sitemap for better image indexing. You can learn more about it [here](https://support.google.com/webmasters/answer/178636?hl=en&ref_topic=4581190).

You can generate image-specific Sitemap like this:

    <?php
    
    use Krystal\Seo\Sitemap\Google\ImageSitemap;
    
    $sitemap = new ImageSitemap();
    $sitemap->addUrl('http://example.com', [
        [
            'loc' => 'http://example.com/image.jpg', // Required
            'caption' => 'My Image', // Optional
            'geo_location' => 'Sweden', // Optional
            'title' => 'Holiday in Sweden', // Optional
            'license' => 'MIT' // Optional
        ],
        
        [
            'loc' => 'http://example.ltd/image-root.jpg', // Required
            'caption' => 'My cover', // Optional
            'geo_location' => 'Germany', // Optional
            'title' => 'Holiday in German', // Optional
            'license' => 'MIT' // Optional
        ]
    ]);
    
    echo $sitemap->render();
    
The following snippet will generate the following output:

    <?xml version="1.0" encoding="UTF-8"?>
    <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
      <url>
        <loc>http://example.com</loc>
        <image:image>
          <image:loc>http://example.com/image.jpg</image:loc>
          <image:caption>My Image</image:caption>
          <image:geo_location>Sweden</image:geo_location>
          <image:title>Holiday in Sweden</image:title>
          <image:license>MIT</image:license>
        </image:image>
        <loc>http://example.com</loc>
        <image:image>
          <image:loc>http://example.ltd/image-root.jpg</image:loc>
          <image:caption>My cover</image:caption>
          <image:geo_location>Germany</image:geo_location>
          <image:title>Holiday in German</image:title>
          <image:license>MIT</image:license>
        </image:image>
      </url>
    </urlset>

The `addUrl()` method accepts two arguments: the primary URL as a string, and an array of images associated with that URL.

You can also use alternate `addUrls()` method to add many URLs at once, like this:

    <?php
    
    use Krystal\Seo\Sitemap\Google\ImageSitemap;

    $sitemap = new ImageSitemap();
    $sitemap->addUrls([
        [
            'loc' => 'http://example.com',
            'images' => [
                // .. As above
            ]
        ],
        [
            'loc' => 'http://example.ltd',
            'images' => [
                // .. As above
            ]
        ]
    ]);


### Localized Sitemap

Google recommends using their localized version of Sitemap for better crawling. You can learn more about it [here](https://support.google.com/webmasters/answer/189077#sitemap%29).

You can use generate it like this:

    <?php
    
    use Krystal\Seo\Sitemap\Google\LocalizedSitemap;
    
    $sitemap = new LocalizedSitemap();
    $sitemap->addUrl('en', 'http://www.example.com/english/page.html', [
        'de' => 'http://www.example.com/german/page.html',
        'fr' => 'http://www.example.com/french/page.html',
    ]);
    
    echo $sitemap->render();

This snippet generates the following output:

    <?xml version="1.0" encoding="UTF-8"?>
    <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">
      <url>
        <loc>http://www.example.com/english/page.html</loc>
        <xhtml:link rel="alternate" hreflang="de" href="http://www.example.com/german/page.html"/>
        <xhtml:link rel="alternate" hreflang="fr" href="http://www.example.com/french/page.html"/>
        <xhtml:link rel="alternate" hreflang="en" href="http://www.example.com/english/page.html"/>
      </url>
    </urlset>

The method `addUrl()` takes 3 arguments. 

 - The first one is a locale of primary language
 - The second argument - the URL of primary language
 - The third argument is key/value array of corresponding translatable locales and their URLs
 
You can also use `addUrls()` method to add many values at once, like this:

    $sitemap->addUrls([
        [
            'hreflang' => 'en',
            'href' => 'http://www.example.com/english/page.html',
            'translations' => [
                'de' => 'http://www.example.com/german/page.html',
                'fr' => 'http://www.example.com/french/page.html',
            ]
        ],
    
        // Add more URLs with their translation maps ...
    ]);


### News Sitemap

Google highly recommends using their News Sitemap for news/blog web-sites. You can learn more about it [here](https://support.google.com/webmasters/answer/9606710?hl=en&ref_topic=4581190).

You can generate News Sitemap like this:

    <?php
    
    use Krystal\Seo\Sitemap\Google\NewsSitemap;
    
    $sitemap = new NewsSitemap();
    
    $sitemap->addUrl('http://www.domain.com/some-another-post', 'Some another post', 'en', '2018-05-10');
    $sitemap->addUrl('http://www.domain.com/some-yet-another-post', 'Some yet another post', 'en', '2018-05-10', 'Get It Started');
    
    echo $sitemap->render();

This outputs the following result:

    <?xml version="1.0" encoding="UTF-8"?>
    <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">
      <url>
        <loc>http://www.domain.com/some-another-post</loc>
        <news:news>
          <news:publication>
            <news:name>Some another post</news:name>
            <news:language>en</news:language>
          </news:publication>
          <news:publication_date>2018-05-10</news:publication_date>
          <news:title>Some another post</news:title>
        </news:news>
      </url>
      <url>
        <loc>http://www.domain.com/some-yet-another-post</loc>
        <news:news>
          <news:publication>
            <news:name>Some yet another post</news:name>
            <news:language>en</news:language>
          </news:publication>
          <news:publication_date>2018-05-10</news:publication_date>
          <news:title>Get It Started</news:title>
        </news:news>
      </url>
    </urlset>

You can also use alternate method `addUrls()` to define many items at once. It goes like this:

    $sitemap->addUrls([
        [
            'loc' => 'http://www.domain.com/some-another-post',
            'name' => 'Some another post',
            'locale' => 'en',
            'date' => '2018-05-10'
        ],
    
        [
            'loc' => 'http://www.domain.com/some-yet-another-post',
            'name' => 'Some yet another post',
            'locale' => 'de',
            'date' => '2018-05-10',
            'title' => 'Get It Started'
        ]
    ]);



### Video Sitemap

For better crawling of Video sites, Google recommends using their dedicated Video Sitemap. You can learn more about this extension [here](https://support.google.com/webmasters/answer/80471?hl=en&ref_topic=4581190).

    <?php
    
    use Krystal\Seo\Sitemap\Google\VideoSitemap;
    
    $sitemap = new VideoSitemap();
    $sitemap->addUrl('http://www.example.com/videos/some_video_landing_page.html', [
        'thumbnail_loc' => 'http://www.example.com/thumbs/123.jpg', // Required
        'title' => 'Grilling steaks for summer', // Required
        'description' => 'Alkis shows you how to get perfectly done steaks every time', // Required
        'content_loc' => 'http://streamserver.example.com/video123.mp4',
        'player_loc' => 'http://www.example.com/videoplayer.php?video=123',
        'duration' => 600, // Recommended
        'expiration_date' => '2021-11-05T19:20:30+08:00', // Recommended
        'rating' => 4.2, //
        'view_count' => 12345, // Optional
        'publication_date' => '2007-11-05T19:20:30+08:00', // Optional
        'family_friendly' => 'yes', // Optional
        'restriction' => [ // Optional
            'relationship' => 'allow',
            'restriction' => 'IE GB US CA'
        ],
        'platform' => 'web', // Optional
        'price' => [ // Optional
            'price' => 1.99,
            'currency' => 'EUR'
        ],
        'requires_subscription' => 'yes', // Optional
        'uploader' => [ // Optional
            'uploader' => 'GrillyMcGrillerson',
            'info' => 'http://www.example.com/users/grillymcgrillerson'
        ],
        'live' => 'yes', // Optional
        'tag' => 'video', // Optional
        'category' => 'Family' // Optional
    ]);

The above example outputs the following Sitemap:

    <?xml version="1.0" encoding="UTF-8"?>
    <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:video="http://www.google.com/schemas/sitemap-video/1.1">
      <url>
        <loc>http://www.example.com/videos/some_video_landing_page.html</loc>
        <video:video>
          <video:thumbnail_loc>http://www.example.com/thumbs/123.jpg</video:thumbnail_loc>
          <video:title>Grilling steaks for summer</video:title>
          <video:description>Alkis shows you how to get perfectly done steaks every time</video:description>
          <video:content_loc>http://streamserver.example.com/video123.mp4</video:content_loc>
          <video:player_loc>http://www.example.com/videoplayer.php?video=123</video:player_loc>
          <video:duration>600</video:duration>
          <video:expiration_date>2021-11-05T19:20:30+08:00</video:expiration_date>
          <video:rating>4.2</video:rating>
          <video:view_count>12345</video:view_count>
          <video:publication_date>2007-11-05T19:20:30+08:00</video:publication_date>
          <video:family_friendly>yes</video:family_friendly>
          <video:restriction relationship="allow">IE GB US CA</video:restriction>
          <video:platform>web</video:platform>
          <video:price currency="EUR">1.99</video:price>
          <video:requires_subscription>yes</video:requires_subscription>
          <video:uploader info="http://www.example.com/users/grillymcgrillerson">GrillyMcGrillerson</video:uploader>
          <video:live>yes</video:live>
          <video:tag>video</video:tag>
          <video:category>Family</video:category>
        </video:video>
      </url>
    </urlset>

The method `addUrl()` takes two arguments:

 * The first one is target URL to be indexed
 * The second one is an array with parameters

You can also add many URLs at once with `addUrls()`. It takes an array, like this:

    $sitemap = new VideoSitemap();
    $sitemap->addUrls([
        [
            'loc' => 'https://some-url.ltd',
            'params' => [
                // Params as above
            ]
        ],
        // Add as many as required
    ]);
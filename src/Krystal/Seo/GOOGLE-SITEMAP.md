
Google-specific Sitemap generators
==========
This document covers usage of specific Google Sitemap implementations

## Image sitemap

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
    
We have just added one URL with corresponding images. You can add as much URLs as needed.
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

The method `addUrl()` takes two arguments. The first one is primary URL string and the second one is an array of corresponding images to that URL.

You can also use alternate `addUrls()` method to add many URLs at once, like this:

    <?php
    
    use Krystal\Seo\Sitemap\Google\ImageSitemap;

    $sitemap = new ImageSitemap();
    $sitemap->addUrls([
        // First URL with its corresponding image items
        [
            'loc' => 'http://example.com',
            'images' => [
                // .. As above
            ]
        ],
        
        // Second URL with its corresponding image items
        [
            'loc' => 'http://example.ltd',
            'images' => [
                // .. As above
            ]
        ]
    ]);

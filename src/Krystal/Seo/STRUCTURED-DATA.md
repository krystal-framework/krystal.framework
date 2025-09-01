Structured Data Markup
======
**Structured Data Markup** is a standardized way of adding extra information (“metadata”) to a webpage’s HTML so that search engines  can better understand the page’s content.

It’s not visible to users on the page itself, but it helps machines interpret meaning, context, and relationships in your content.

## Breadcrumbs

 **Breadcrumb structured data** is a specific type of structured data markup that tells search engines about the **navigational path (breadcrumbs)** of a webpage.
 
Usage example:

    <?php
    
    use Krystal\Seo\StructuredData;
    use Krystal\Form\Element;
    
    $items = [
        ['name' => 'Home', 'url' => 'https://example.com/'],
        ['name' => 'Blog', 'url' => 'https://example.com/blog'],
        ['name' => 'Post'] // last item, current page, no URL
    ];
    
    $schema = (new StructuredData())->generateBreadcrumbSchema($items);
    echo Element::jsonLd($schema);

This will output the following:

    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "BreadcrumbList",
      "itemListElement": [
        {
          "@type": "ListItem",
          "position": 1,
          "name": "Home",
          "item": "https://example.com/"
        },
        {
          "@type": "ListItem",
          "position": 2,
          "name": "Blog",
          "item": "https://example.com/blog"
        },
        {
          "@type": "ListItem",
          "position": 3,
          "name": "Post"
        }
      ]
    }
    </script>

## FAQ

**FAQ structured data** is a type of Schema.org markup you can add to a webpage that contains a list of **frequently asked questions and their answers**.

It helps **search engines** understand that the content is a Q&A format, and it allows them to display those questions and answers directly in search results as **rich results** (collapsible FAQ dropdowns under your page link).

    <?php
    
    use Krystal\Seo\StructuredData;
    use Krystal\Form\Element;
    
    $items = [
        [
            'question' => 'What is your return policy?',
            'answer' => 'You can return any item within 30 days of purchase.'
        ],
        [
            'question' => 'Do you ship internationally?',
            'answer' => 'Yes, we ship to most countries worldwide.'
        ]
    ];
    
    $schema = (new StructuredData())->generateFAQSchema($items);
    echo Element::jsonLd($schema);
    

This will output the following:

    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "FAQPage",
      "mainEntity": [
        {
          "@type": "Question",
          "name": "What is your return policy?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "You can return any item within 30 days of purchase."
          }
        },
        {
          "@type": "Question",
          "name": "Do you ship internationally?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Yes, we ship to most countries worldwide."
          }
        }
      ]
    }
    </script>
    
    
## Local business

**Local Business Schema** is a type of structured data markup that you add to your website so that **search engines  can better understand details about your business**.

Usage example

    <?php
        
    use Krystal\Seo\StructuredData;
    use Krystal\Form\Element;
    
    $businessData = [
        'name' => "Dave's Pizza",
        'url' => "https://example.com",
        'image' => "https://example.com/logo.png",
        'phone' => "+1-555-123-4567",
        'address' => "123 Main Street",
        'city' => "New York",
        'region' => "NY",
        'postalCode' => "10001",
        'country' => "US",
        'latitude' => 40.7128,
        'longitude' => -74.0060,
        'openingHours' => [
            "Mo-Fr 11:00-22:00",
            "Sa-Su 12:00-23:00"
        ],
        'priceRange' => "$$"
    ];
    
    $schema = (new StructuredData())->generateLocalBusinessSchema($items);
        echo Element::jsonLd($schema);

This will output the following:

    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "LocalBusiness",
      "@id": "https://example.com/#localbusiness",
      "name": "Dave's Pizza",
      "url": "https://example.com",
      "image": "https://example.com/logo.png",
      "telephone": "+1-555-123-4567",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "123 Main Street",
        "addressLocality": "New York",
        "addressRegion": "NY",
        "postalCode": "10001",
        "addressCountry": "US"
      },
      "geo": {
        "@type": "GeoCoordinates",
        "latitude": 40.7128,
        "longitude": -74.006
      },
      "openingHours": [
        "Mo-Fr 11:00-22:00",
        "Sa-Su 12:00-23:00"
      ],
      "priceRange": "$$"
    }
    </script>

## Organization

**Organization schema** is a type of **structured data markup** (from Schema.org) that describes details about an organization — like a business, non-profit, school, or brand.

Usage example:

    <?php
        
    use Krystal\Seo\StructuredData;
    use Krystal\Form\Element;

    $params = [
        'siteName' => "My Awesome Company",
        'siteUrl' => "https://example.com",
        'logo' => [
            'url' => "https://example.com/logo.png",
            'width' => 200,
            'height' => 50
        ],
        'socialProfiles' => [
            "https://facebook.com/mycompany",
            "https://x.com/mycompany"
        ]
    ];
    
    $schema = (new StructuredData())->generateOrganizationSchema($items);
    echo Element::jsonLd($schema);

This will output the following:

    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "My Awesome Company",
      "url": "https://example.com",
      "logo": {
        "@type": "ImageObject",
        "@id": "https://example.com/#logo",
        "url": "https://example.com/logo.png",
        "width": 200,
        "height": 50,
        "caption": "My Awesome Company"
      },
      "sameAs": [
        "https://facebook.com/mycompany",
        "https://x.com/mycompany"
      ]
    }
    </script>

## Web site

The **WebSite schema** is a type of **structured data** (JSON-LD) defined by Schema.org. It helps search engines understand the **overall identity of your website**.

Instead of describing a single page, article, or product, the `WebSite` schema gives **context about the entire site**.

    <?php
    
    use Krystal\Seo\StructuredData;
    use Krystal\Form\Element;
    
    $params = [
        'siteUrl'  => 'https://example.com',
        'siteName' => 'My Awesome Website',
        'language' => 'en-US',
        'searchUrl'=> 'https://example.com/search?q={search_term_string}' //  Optional
    ];
    
     $schema = (new StructuredData())->generateWebsiteSchema($items);
     echo Element::jsonLd($schema);

This will output:

    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebSite",
      "@id": "https://example.com/#website",
      "url": "https://example.com",
      "name": "My Awesome Website",
      "publisher": {
        "@id": "https://example.com/#organization"
      },
      "inLanguage": "en-US",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "https://example.com/search?q={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    }
    </script>


## Web page

**WebPage schema** is a type of structured data defined by [Schema.org](https://schema.org?utm_source=chatgpt.com) that provides search engines with detailed information about an individual web page.

Usage example:

    <?php
    
    use Krystal\Seo\StructuredData;
    use Krystal\Form\Element;
    
    $pageData = [
        'url'         => 'https://example.com/about-us',
        'name'        => 'About Us',
        'description' => 'Learn more about our company and mission.',
        'createdAt'   => '2020-05-01 10:00:00',
        'updatedAt'   => 'August 15, 2025 14:45',
        'language'    => 'en-US',
        'siteUrl'     => 'https://example.com',
        'author'      => 'Jane Doe'
    ];
    
    $schema = (new StructuredData())->generateWebPageSchema($items);
    echo Element::jsonLd($schema);

This will output:

    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebPage",
      "@id": "https://example.com/about-us#webpage",
      "url": "https://example.com/about-us",
      "name": "About Us",
      "description": "Learn more about our company and mission.",
      "datePublished": "2020-05-01T10:00:00+00:00",
      "dateModified": "2025-08-15T14:45:00+00:00",
      "inLanguage": "en-US",
      "isPartOf": {
        "@id": "https://example.com/#website"
      },
      "author": {
        "@type": "Person",
        "name": "Jane Doe"
      }
    }
    </script>


## Article

**Article schema** is a type of structured data defined by Schema.org. It helps search engines understand that a piece of content on your site is an **article** — such as a news story, blog post, or general written content.

Usage example:

    <?php
    
    use Krystal\Seo\StructuredData;
    use Krystal\Form\Element;
    
    $postData = [
        'url'         => 'https://example.com/blog/seo-tips',
        'title'       => '10 Essential SEO Tips for Beginners',
        'description' => 'Learn the top 10 SEO tips every beginner should know.',
        'image'       => 'https://example.com/images/seo-tips.jpg',
        'author'      => ['name' => 'Jane Doe'],
        'siteName'    => 'Example Blog',
        'logo'        => 'https://example.com/images/logo.png',
        'publishedAt' => '2023-07-15 10:00:00',
        'updatedAt'   => '2023-07-20 14:30:00',
        'language'    => 'en-US',
    ];
    
        
    $schema = (new StructuredData())->generateArticleSchema($items);
    echo Element::jsonLd($schema);

This will output:

    {
      "@context": "https://schema.org",
      "@type": "Article",
      "@id": "https://example.com/blog/seo-tips#article",
      "url": "https://example.com/blog/seo-tips",
      "headline": "10 Essential SEO Tips for Beginners",
      "description": "Learn the top 10 SEO tips every beginner should know.",
      "image": "https://example.com/images/seo-tips.jpg",
      "author": {
        "@type": "Person",
        "name": "Jane Doe"
      },
      "publisher": {
        "@type": "Organization",
        "name": "Example Blog",
        "logo": {
          "@type": "ImageObject",
          "url": "https://example.com/images/logo.png"
        }
      },
      "datePublished": "2023-07-15T10:00:00+00:00",
      "dateModified": "2023-07-20T14:30:00+00:00",
      "inLanguage": "en-US"
    }


## Collection Page

A **CollectionPage** is a schema.org type that describes a webpage which groups together a list of **items**.

Examples of where it can be used:

- Blog category pages (e.g., `/blog/seo/`)    
- Product listing pages (e.g., `/shop/shoes/`)
- Portfolio overview pages (e.g., `/projects/`)
- Events listing (e.g., `/events/`)
 
Usage example:

    <?php
    
    use Krystal\Seo\StructuredData;
    use Krystal\Form\Element;
    
    $pageData = [
        'url' => 'https://example.com/blog/seo',
        'name' => 'SEO Articles',
        'description' => 'Read our latest blog posts and guides about SEO.',
        'language' => 'en-US',
        'siteUrl' => 'https://example.com',
        'itemType' => 'Article',
        'items' => [
            [
                'id' => 'https://example.com/blog/seo-guide#article',
                'url' => 'https://example.com/blog/seo-guide',
                'name' => 'Ultimate Guide to SEO',
                'datePublished' => '2023-07-12',
                'dateModified' => '2023-08-05'
            ],
            [
                'id' => 'https://example.com/blog/link-building#article',
                'url' => 'https://example.com/blog/link-building',
                'name' => 'Top 10 Link Building Strategies',
                'datePublished' => '2026-06-20',
                'dateModified' => '2026-07-01'
            ]
        ]
    ];
    
    $schema = (new StructuredData())->generateCollectionPageSchema($items);
    echo Element::jsonLd($schema);

This will output the following:

    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "CollectionPage",
      "@id": "https://example.com/blog/seo#webpage",
      "url": "https://example.com/blog/seo",
      "name": "SEO Articles",
      "description": "Read our latest blog posts and guides about SEO.",
      "inLanguage": "en-US",
      "isPartOf": {
        "@id": "https://example.com/#website"
      },
      "mainEntity": [
        {
          "@type": "Article",
          "@id": "https://example.com/blog/seo-guide#article",
          "url": "https://example.com/blog/seo-guide",
          "name": "Ultimate Guide to SEO",
          "datePublished": "2023-07-12",
          "dateModified": "2023-08-05"
        },
        {
          "@type": "Article",
          "@id": "https://example.com/blog/link-building#article",
          "url": "https://example.com/blog/link-building",
          "name": "Top 10 Link Building Strategies",
          "datePublished": "2026-06-20",
          "dateModified": "2026-07-01"
        }
      ]
    }
    </script>


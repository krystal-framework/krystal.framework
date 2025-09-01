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


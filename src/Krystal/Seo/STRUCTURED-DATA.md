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
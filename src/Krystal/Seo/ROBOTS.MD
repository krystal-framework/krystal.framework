
Robots
======

A `robots.txt` file is used to tell web crawlers (such as Googlebot, Bingbot, or AI crawlers) which parts of your website they are allowed or not allowed to access.

## Usage

This tool provides a simple, fluent API for generating valid `robots.txt` files programmatically.

### Basic example

    <?php
    
    use Krystal\Seo\Robots; 
    
    $robots = new Robots(); 
    $robots->addComment('Default generated robots.txt')
           ->addUserAgent('*')
           ->addDisallow([
              '/config/',
              '/modules/'
           ])
           ->addAllow('/images/')
           ->addBreak()
           ->addHost('example.com')
           ->addBreak()
           ->addSitemap([
               'https://example.com/sitemap-1.xml', 
               'https://example.com/sitemap-2.xml'
           ]);
           
    echo $robots->render();

This outputs:

    # Default generated robots.txt
    User-agent: *
    Disallow: /config/
    Disallow: /modules/
    Allow: /images/
    Host: example.com
    Sitemap: https://example.com/sitemap-1.xml
    Sitemap: https://example.com/sitemap-2.xml

The following methods accept either a **single value or an array of values**:

-   `addUserAgent()`
-   `addAllow()`
-   `addDisallow()`
-   `addNoindex()`
-   `addCleanParam()`
-   `addSitemap()`
    


## Additional directives

The following crawler-specific directives are also supported:

    $robots->addUserAgent('*')
           ->addCrawlDelay(2)
           ->addRequestRate('1/5')
           ->addCleanParam('utm_source')
           ->addNoindex('/private/');

Supported directives include:

-   `Crawl-delay` (Bing, Yandex)
-   `Request-rate` (Bing)
-   `Clean-param` (Yandex)
-   `Noindex` (Yandex)
-   `Host` (Yandex, single instance enforced)
    

## AI crawler control

AI and LLM crawlers are controlled via their **user-agent names**. No special directive is required.

Example:

    $robots->addUserAgent('GPTBot')
           ->addDisallow('*')
           ->addBreak()
           ->addUserAgent('Google-Extended')
           ->addDisallow('*');

## Validation behavior

The generator performs lightweight validation:

-   Paths must start with `/`, be empty, or be `*`
-   Sitemap values must be valid absolute URLs
-   `Crawl-delay` must be a non-negative number
-   Only one `Host` directive is allowed
    

Invalid values result in an `InvalidArgumentException`.

## Saving

You can save the generated `robots.txt` file to a directory using the `save($dir)` method:

    $robots->save('/var/www/html');

The method returns a boolean value indicating whether the save operation was successful.

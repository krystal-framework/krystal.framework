Robots
=====

### Why

A robots.txt file is used to tell web crawlers (like Googlebot, Bingbot, or other automated indexing tools) what parts of your website they are allowed or not allowed to access.

### Usage

With this tool, you can quickly generate content for your robots.txt file. A basic example:

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

The `addUserAgent()`, `addDisallow()`, `addAllow()`, and `addSitemap()` methods can accept either a single value or an array of values.

### Saving

You can also save the generated robots.txt file to a specific directory using the save($dir) method, where $dir is the path to the target directory. The method returns a boolean value indicating whether the save operation was successful.
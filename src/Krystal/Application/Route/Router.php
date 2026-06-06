<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\Route;

use Krystal\Application\Route\RouteMatch;
use Krystal\Application\Route\RouterInterface;

/**
 * Here we are dealing with several concepts:
 * 
 * 1. URI Template
 * 2. Actual URI
 * 3. Extracting parameters. This is a difference between matched URI template and actual URI string
 */
final class Router implements RouterInterface
{
    /**
     * Default reg-ex patterns with its place-holders
     * 
     * @var array
     */
    private $replacements = [
        '(:var)' => '([^/]*)'
    ];

    /**
     * Cache for compiled regular expressions
     * 
     * @var array
     */
    private $compiled = [];

    /**
     * Process redirect
     * 
     * @param string $uri Current URI
     * @param array $map Map of old => new relations
     * @return void
     */
    public function processRedirect($uri, array $map)
    {
        // O(1) Quick lookup instead of linear O(N) loop
        if (isset($map[$uri])) {
            header('HTTP/1.1 301 Moved Permanently'); 
            header(sprintf('Location: %s', $map[$uri])); 
            exit();
        }
    }

    /**
     * Matches a URI string against a route map
     * 
     * @param string $segment The actual segment to match against
     * @param array $map Target route map to compare against
     * @return boolean|\Krystal\Application\Route\RouteMatch
     */
    public function match($segment, array $map)
    {
        // Strip the query string before matching routes to prevent capturing query params 
        // as endpoint arguments in Controller methods
        if (($pos = strpos($segment, '?')) !== false) {
            $segment = substr($segment, 0, $pos);
        }

        foreach ($map as $index => $uriTemplate) {
            $matches = [];

            if (preg_match($this->createRegEx($uriTemplate), $segment, $matches) === 1) {
                // Remove the full match to leave only the captured variables
                $matchedURI = array_shift($matches);

                $routeMatch = new RouteMatch();
                $routeMatch->setMatchedUri($matchedURI)
                           ->setMatchedUriTemplate($uriTemplate)
                           ->setVariables($matches);

                return $routeMatch;
            }
        }

        // By default, we have no matches
        return false;
    }

    /**
     * Creates a regular expression according to URI template
     * 
     * @param string $uriTemplate string
     * @return string Prepared regular expression
     */
    private function createRegEx($uriTemplate)
    {
        // Return instantly if we already compiled this URI template
        if (isset($this->compiled[$uriTemplate])) {
            return $this->compiled[$uriTemplate];
        }

        // Quote the template to safely parse literal characters (e.g. dots, hyphens)
        $pattern = preg_quote($uriTemplate, '~');

        // Quote placeholders so they match their quoted representation in the template
        $placeholders = array_map(function($placeholder) {
            return preg_quote($placeholder, '~');
        }, $this->getPlaceholders());

        $pattern = str_replace($placeholders, $this->getPatterns(), $pattern);

        return $this->compiled[$uriTemplate] = '~^' . $pattern . '$~i';
    }

    /**
     * Return place-holders only
     * 
     * @return array
     */
    private function getPlaceholders()
    {
        return array_keys($this->replacements);
    }

    /**
     * Returns regular expressions
     * 
     * @return array
     */
    private function getPatterns()
    {
        return array_values($this->replacements);
    }
}

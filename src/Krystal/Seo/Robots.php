<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Seo;

use InvalidArgumentException;

final class Robots
{
    /**
     * Default filename used by search engines
     * 
     * @const string
     */
    const FILENAME = 'robots.txt';

    /**
     * Lines to be rendered
     * 
     * @var array
     */
    private $lines = array();

    /**
     * Whether host has been added (to prevent multiple ones)
     * 
     * @var boolean
     */
    private $hostAdded = false;

    /**
     * Renders content of robots
     * 
     * @return string
     */
    public function render()
    {
        return implode(PHP_EOL, $this->lines);
    }

    /**
     * Writes robots.txt into the specified directory
     *
     * @param string $dir Directory path
     * @throws \InvalidArgumentException if the directory does not exist
     * @return bool True on success, false on failure
     */
    public function save($dir)
    {
        if (!is_dir($dir)) {
            throw new InvalidArgumentException(
                sprintf('Directory does not exist: "%s"', $dir)
            );
        }

        $path = rtrim($dir, '/\\') . '/' . self::FILENAME;
        return file_put_contents($path, $this->render()) !== false;
    }

    /**
     * Validate robots.txt path
     * 
     * @param string $path
     * @return void
     */
    private function validatePath($path)
    {
        if ($path === '') {
            return;
        }

        if (!is_string($path) || ($path !== '*' && $path[0] !== '/')) {
            throw new InvalidArgumentException(
                sprintf('Invalid robots path: "%s"', $path)
            );
        }
    }

    /**
     * Validates a path or array of paths for robots directives
     *
     * @param string|array $value Path(s) to validate
     * @return void
     * @throws \InvalidArgumentException if a path is invalid
     */
    private function validatePathValue($value)
    {
        foreach ((array) $value as $item) {
            $this->validatePath($item);
        }
    }

    /**
     * Validate absolute URL
     * 
     * @param string $url
     * @return void
     */
    private function validateUrl($url)
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException(
                sprintf('Invalid URL: "%s"', $url)
            );
        }
    }

    /**
     * Adds one or more lines for a directive
     *
     * @param string $key Directive name
     * @param string|array|null $value Directive value(s)
     * @return \Krystal\Seo\Robots
     */
    private function addLine($key, $value = null)
    {
        if ($key && $value !== null) {
            $line = sprintf('%s: %s', $key, $value);
        } else {
            $line = $key;
        }

        $this->lines[] = $line;
        return $this;
    }

    /**
     * Add single or many lines at once
     * 
     * @param string $key
     * @param string|array $value Optional value
     * @return \Krystal\Seo\Robots
     */
    private function addLines($key, $value = null)
    {
        if (is_array($value)) {
            foreach ($value as $item) {
                $this->addLine($key, $item);
            }
        } else {
            $this->addLine($key, $value);
        }

        return $this;
    }

    /**
     * Adds a break
     * 
     * @return \Krystal\Seo\Robots
     */
    public function addBreak()
    {
        return $this->addLine(null);
    }

    /**
     * Adds a comment
     * 
     * @param string $value
     * @return \Krystal\Seo\Robots
     */
    public function addComment($value)
    {
        return $this->addLine(sprintf('# %s', $value));
    }

    /**
     * Adds a user-agent directive
     * 
     * @param string|array $value
     * @return \Krystal\Seo\Robots
     */
    public function addUserAgent($value)
    {
        return $this->addLines('User-agent', $value);
    }

    /**
     * Adds Allow directive
     * 
     * @param string|array $value
     * @return \Krystal\Seo\Robots
     */
    public function addAllow($value)
    {
        $this->validatePathValue($value);
        return $this->addLines('Allow', $value);
    }

    /**
     * Adds Disallow directive
     * 
     * @param string|array $value
     * @return \Krystal\Seo\Robots
     */
    public function addDisallow($value)
    {
        $this->validatePathValue($value);
        return $this->addLines('Disallow', $value);
    }

    /**
     * Adds Sitemap directive
     * 
     * @param string|array $value
     * @return \Krystal\Seo\Robots
     */
    public function addSitemap($value)
    {
        if (is_array($value)) {
            foreach ($value as $item) {
                $this->validateUrl($item);
                $this->addLine('Sitemap', $item);
            }
        } else {
            $this->validateUrl($value);
            $this->addLine('Sitemap', $value);
        }

        return $this;
    }

    /**
     * Adds Host directive (only one allowed)
     *
     * @param string $value Hostname
     * @throws \InvalidArgumentException if host is invalid or already added
     * @return \Krystal\Seo\Robots
     */
    public function addHost($value)
    {
        if ($this->hostAdded === true) {
            throw new InvalidArgumentException('Only one Host directive is allowed.');
        }

        // Simple hostname validation
        if (!preg_match('/^[a-zA-Z0-9.-]+$/', $value)) {
            throw new InvalidArgumentException(
                sprintf('Invalid host value: "%s"', $value)
            );
        }

        $this->hostAdded = true;
        return $this->addLine('Host', $value);
    }

    /**
     * Adds Crawl-delay directive
     *
     * @param int|float $value Non-negative number of seconds
     * @throws \InvalidArgumentException if less than 0 or non-numeric
     * @return \Krystal\Seo\Robots
     */
    public function addCrawlDelay($value)
    {
        if (!is_numeric($value) || $value < 0) {
            throw new InvalidArgumentException(
                'Crawl-delay must be a non-negative number'
            );
        }

        return $this->addLine('Crawl-delay', $value);
    }

    /**
     * Adds Request-rate directive
     *
     * @param string $value
     * @return \Krystal\Seo\Robots
     */
    public function addRequestRate($value)
    {
        return $this->addLine('Request-rate', $value);
    }

    /**
     * Adds Clean-param directive
     *
     * @param string|array $value
     * @return \Krystal\Seo\Robots
     */
    public function addCleanParam($value)
    {
        return $this->addLines('Clean-param', $value);
    }

    /**
     * Adds Noindex directive (used by Yandex)
     *
     * @param string|array $value Path(s) or pattern(s) to noindex
     * @throws \InvalidArgumentException if a path is invalid
     * @return \Krystal\Seo\Robots
     */
    public function addNoindex($value)
    {
        $this->validatePathValue($value);
        return $this->addLines('Noindex', $value);
    }
}

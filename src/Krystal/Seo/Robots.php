<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Seo;

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
     * Renders content of robots
     * 
     * @return string
     */
    public function render()
    {
        return implode(PHP_EOL, $this->lines);
    }

    /**
     * Writes robots.txt into a directory
     * 
     * @param string $dir Directory path
     * @return boolean Depending on success
     */
    public function save($dir)
    {
        $path = $dir . '/' . self::FILENAME;
        return file_put_contents($path, $this->render());
    }

    /**
     * Adds a line to the internal stack
     * 
     * @param string $key
     * @param mixed $value Optional value
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
        return $this->addLines('Sitemap', $value);
    }

    /**
     * Adds Host directive
     * 
     * @param string $value
     * @return \Krystal\Seo\Robots
     */
    public function addHost($value)
    {
        return $this->addLine('Host', $value);
    }
}

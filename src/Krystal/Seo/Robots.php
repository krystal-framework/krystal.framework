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
     * Lines to be rendered
     * 
     * @var array
     */
    private $lines = array();

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
     * Renders content of robots
     * 
     * @return string
     */
    public function render()
    {
        return implode(PHP_EOL, $this->lines);
    }

    /**
     * Adds a break
     * 
     * @return \Krystal\Seo\Robots
     */
    public function addBreak()
    {
        return $this->addLine(PHP_EOL);
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
     * @param string $value
     * @return \Krystal\Seo\Robots
     */
    public function addUserAgent($value)
    {
        return $this->addLine('User-agent', $value);
    }

    /**
     * Adds Allow directive
     * 
     * @param string $value
     * @return \Krystal\Seo\Robots
     */
    public function addAllow($value)
    {
        return $this->addLine('Allow', $value);
    }

    /**
     * Adds Disallow directive
     * 
     * @param string $value
     * @return \Krystal\Seo\Robots
     */
    public function addDisallow($value)
    {
        return $this->addLine('Disallow', $value);
    }

    /**
     * Adds Sitemap directive
     * 
     * @param string $value
     * @return \Krystal\Seo\Robots
     */
    public function addSitemap($value)
    {
        return $this->addLine('Sitemap', $value);
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

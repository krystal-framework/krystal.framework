<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Seo\Sitemap;

use UnexpectedValueException;

final class Validator
{
    /**
     * Throws an error
     * 
     * @param string $tagName
     * @param string $value
     * @throws \UnexpectedValueException On call
     * @return void
     */
    public static function throwError($tagName, $value)
    {
        throw new UnexpectedValueException(sprintf('Invalid `%s` parameter encountered - %s', $tagName, $value));
    }

    /**
     * Checks whether $loc is valid
     * 
     * @param string $loc
     * @return boolean
     */
    public static function isLoc($loc)
    {
        return (bool) filter_var($loc, \FILTER_VALIDATE_URL);
    }

    /**
     * Checks whether priority is valid
     * 
     * @param string $priority
     * @return boolean
     */
    public static function isPriority($priority)
    {
        return in_array($priority, array(
            '0.0',
            '0.1',
            '0.2',
            '0.3',
            '0.4',
            '0.5',
            '0.6',
            '0.7',
            '0.8',
            '0.9',
            '1.0'
        ));
    }

    /**
     * Checks whether changefreq is valid
     * 
     * @param string $changefreq
     * @return boolean
     */
    public static function isChangefreq($changefreq)
    {
        return in_array($changefreq, array(
            SitemapGenerator::FREQ_ALWAYS,
            SitemapGenerator::FREQ_HOURLY,
            SitemapGenerator::FREQ_DAILY,
            SitemapGenerator::FREQ_WEEKLY,
            SitemapGenerator::FREQ_MONTHLY,
            SitemapGenerator::FREQ_YEARLY,
            SitemapGenerator::FREQ_NEVER
        ));
    }
}

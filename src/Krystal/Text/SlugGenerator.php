<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Text;

use InvalidArgumentException;

final class SlugGenerator implements SlugGeneratorInterface
{
    /**
     * Whether to romanize the text
     * 
     * @var boolean
     */
    private $romanization;

    /**
     * State initialization
     * 
     * @param boolean $romanization
     * @return void
     */
    public function __construct($romanization = true)
    {
        $this->romanization = $romanization;
    }

    /**
     * Removes extra dashes
     * 
     * @param string $string
     * @return string
     */
    private function removeExtraDashes($string)
    {
        return str_replace(array('--'), '', $string);
    }

    /**
     * Removes undesired characters from a string
     * 
     * @param string $string
     * @return string
     */
    private function removeUndesiredChars($string)
    {
        // Undesired characters in URI string
        $undesired = array('!', '~', '`', '@', '#', '$', 
                            '%', '^', '&', '*', '(', ')', 
                            '=', '\\', '?', '"', '№', ';', 
                            ':', '[', ']', '»', '«', '--', 
                            ',', '.', "'", '/', '“', '”', '’', '‘', '+');

        return str_replace($undesired, '', $string);
    }

    /**
     * Replaces white spaces in a string
     * 
     * @param string $string
     * @return string
     */
    private function replaceWt($string)
    {
        // Replace all spaces with - (aware of UTF-8)
        return preg_replace("/\\s+/iu", "-", $string);
    }

    /**
     * Romanizes a string
     * 
     * @param string $string
     * @return string
     */
    private function romanize($string)
    {
        return ForeignChars::romanize($string);
    }

    /**
     * Make a string lowercase
     * 
     * @param string $string
     * @return string
     */
    private function lowercase($string)
    {
        return mb_strtolower($string, 'UTF-8');
    }
    
    /**
     * Trims white-spaces from left and right sides
     * 
     * @param string $slug
     * @return string
     */
    private function trim($slug)
    {
        $slug = rtrim($slug);
        $slug = ltrim($slug);

        return $slug;
    }

    /**
     * Determines whether slug is numeric
     * 
     * @param string $slug
     * @return boolean
     */
    private function isNumericSlug($slug)
    {
        return preg_match('~-[0-9]~', $slug);
    }

    /**
     * Creates unique slug
     * 
     * @param string $slug
     * @return string
     */
    private function createUniqueNumericSlug($slug)
    {
        if ($this->isNumericSlug($slug)) {
            // Extract last number and increment it
            $number = substr($slug, -1, 1);
            $number++;

            // Replace with new number
            $string = substr($slug, 0, strlen($slug) - 1) . $number;

            return $string;
        } else {
            return $slug;
        }
    }

    /**
     * Returns unique slug
     * 
     * @param callable $callback Callback function
     * @param string $slug
     * @param array $args Extra arguments to be passed to callback function
     * @throws \InvalidArgumentException If $callback isn't callable
     * @return string
     */
    public function getUniqueSlug($callback, $slug, array $args = array())
    {
        if (!is_callable($callback)) {
            throw new InvalidArgumentException(
                sprintf('First argument must be callable, received "%s"', gettype($callback))
            );
        }

        $count = 0;

        while (true) {
            $count++;

            if (call_user_func_array($callback, array_merge(array($slug), $args))) {
                // If dash can't be found, then add first
                if (!$this->isNumericSlug($slug)) {
                    $slug = sprintf('%s-%s', $slug, $count);
                } else {
                    $slug = $this->createUniqueNumericSlug($slug);
                }

            } else {
                break;
            }
        }

        return $slug;
    }

    /**
     * Generates a slug
     * 
     * @param string $string Target string
     * @return string
     */
    public function generate($string)
    {
        if ($this->romanization === true) {
            $string = $this->romanize($string);
        }

        $string = $this->lowercase($string);
        $string = $this->removeUndesiredChars($string);
        $string = $this->trim($string);
        $string = $this->replaceWt($string);
        $string = $this->removeExtraDashes($string);

        // Strip all invisible characters
        $string = preg_replace('/\p{C}+/u', '', $string);

        return $string;
    }
}

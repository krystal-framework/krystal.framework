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

class TextUtils
{
    /**
     * Trims a string
     * 
     * @param string $string
     * @param integer $maxLen
     * @param string $after
     * @return string
     */
    public static function trim($string, $maxLen, $after = ' .... ')
    {
        $trimmer = new TextTrimmer($after);
        return $trimmer->trim($string, $maxLen);
    }

    /**
     * Sluggifies a string
     * 
     * @param string $string
     * @param boolean $romanize Whether to romanize the string as well
     * @return string
     */
    public static function sluggify($string, $romanize = true)
    {
        $generator = new SlugGenerator($romanize);
        return $generator->generate($string);
    }

    /**
     * Romanizes a string
     * 
     * @param string $string
     * @return string
     */
    public static function romanize($string)
    {
        return ForeignChars::romanize($string);
    }

    /**
     * Converts a string to studly case
     * 
     * @param string $input
     * @return string
     */
    public static function studly($input)
    {
        $input = mb_convert_case($input, \MB_CASE_TITLE, 'UTF-8');
        $input = str_replace(['-', '_'], ' ', $input);
        $input = str_replace(' ', '', $input);

        return $input;
    }

    /**
     * Explodes a text into sentences
     * 
     * @param string $text
     * @param string $carriage Carriage return to be used to detect new lines
     * @return array
     */
    public static function explodeText($text, $carriage = "\r")
    {
        // Default delimiters
        $delimiters = array('!', '?', '.');

        if ($carriage !== null) {
            array_push($delimiters, $carriage);
        }

        return self::multiExplode($text, $delimiters);
    }

    /**
     * Explodes a string into array supporting several delimiters
     * 
     * @param string $string
     * @param array $delimiters A collection of delimiters
     * @param boolean $keepDelimiters Whether to keep delimiters on exploding
     * @return array
     */
    public static function multiExplode($string, array $delimiters, $keepDelimiters = true)
    {
        if ($keepDelimiters === true) {
            // Ensure special characters are escaped
            foreach ($delimiters as &$delimiter) {
                $delimiter = preg_quote($delimiter);
            }

            // RegEx fragment with delimiters
            $fragment = implode('|', $delimiters);
            $regEx = sprintf('@(?<=%s)@', $fragment);
            return preg_split($regEx, $string, -1, PREG_SPLIT_DELIM_CAPTURE);

        } else {

            $string = str_replace($delimiters, $delimiters[0], $string);
            return explode($delimiters[0], $string);
        }
    }
}

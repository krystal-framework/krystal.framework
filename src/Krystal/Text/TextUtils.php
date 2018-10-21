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

use UnexpectedValueException;

class TextUtils
{
    /**
     * Generates serial number like XXXXX-XXXXX-XXXXX-XXXXX, the mask can be overridden
     * 
     * @param string $id
     * @param bool $unique Whether output must be always unique
     * @param bool $upper Whether output must be in uppercase
     * @param int $length The length of the serial itself
     * @param int $portion The length of each portion
     * @return string
     */
    public static function serial($id, $unique = true, $upper = true, $length = 25, $portion = 5)
    {
        // If unique is defined
        if ($unique === true) {
            $salt = substr(sha1(mt_rand()), 0, 20);
            $id .= $salt;
        }

        // MD5 is great enough
        $hash = md5($id);

        // Make the hash more unique
        for ($i = 0; $i < 10; $i++) {
            $hash = md5($hash);
        }

        if ($upper === true) {
            $hash = strtoupper($hash);
        }

        return implode('-', str_split(substr($hash, 0, $length), $portion));
    }

    /**
     * Normalizes column name
     * 
     * @param string $string
     * @return string
     */
    public static function normalizeColumn($string)
    {
        $parts = explode('_', $string);

        foreach ($parts as &$part) {
            $part = ucfirst($part);
        }

        return join(' ', $parts);
    }

    /**
     * Creates a unique string
     * 
     * @return string
     */
    public static function uniqueString()
    {
        $id = uniqid(microtime() . rand(), true);
        return md5($id);
    }

    /**
     * Creates a random string with fixed length
     * 
     * @param integer $length
     * @param string $method Dictionary method
     * @throws \UnexpectedValueException If unsupported dictionary method provided
     * @return string
     */
    public static function randomString($length, $method = 'alnum')
    {
        $types = array(
            'alpha' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'alnum' => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'numeric' => '0123456789'
        );

        if (isset($types[$method])) {
            $dictionary = $types[$method];
        } else {
            throw new UnexpectedValueException(sprintf('Unsupported dictionary type provided "%s"', $method));
        }

        $output = '';

        for ($i = 0; $i < $length; $i++) {
            $output .= substr($dictionary, mt_rand(0, strlen($dictionary) -1), 1);
        }

        return $output;
    }

    /**
     * Returns needle positions
     * 
     * @param string $haystack
     * @param string $needle
     * @return array An array with starting and ending positions for each match
     */
    public static function getNeedlePositions($haystack, $needle)
    {
        $start = 0;
        $result = array();
        $needleLength = strlen($needle);

        while (($pos = strpos($haystack, $needle, $start)) !== false) {
            $start = $pos + 1;

            // Calculate starting and ending positions
            $startPos = $pos;
            $endPos = $pos + $needleLength;

            // Save them
            $result[$startPos] = $endPos;
        }

        return $result;
    }

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
        return mb_substr($string, 0, $maxLen, 'UTF-8') . $after;
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
     * Converts target string to snake case
     * https://en.wikipedia.org/wiki/Snake_case
     * 
     * @param string $target Target string (i.e Hello World or camelCase)
     * @return string (hello_world or camel_case)
     */
    public static function snakeCase($target)
    {
        // Common patterns
        $patterns = array(
            '/([a-z\d])([A-Z])/',
            '/([^_])([A-Z][a-z])/'
        );

        $output = preg_replace($patterns, '$1_$2', $target);

        // Ensure output is in lowercase
        return strtolower($output);
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
        $input = str_replace(array('-', '_'), ' ', $input);
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

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
use Closure;

class TextUtils
{
    /**
     * Checks whether a string contains a given substring.
     *
     * This method works with both modern and legacy PHP versions.
     *
     * @param string $haystack The string to search in.
     * @param string $needle The substring to search for.
     * @return bool True if $needle is found in $haystack, false otherwise.
     */
    public static function contains($haystack, $needle)
    {
        if (function_exists('str_contains')) {
            return str_contains($haystack, $needle);
        } else {
            // Polyfill for legacy PHP versions
            return $needle !== '' && mb_strpos($haystack, $needle) !== false;
        }
    }

    /**
     * Splits a string into an array of lines, trimming whitespace from each line.
     *
     * @param string $string The input string containing one or more lines.
     * @return array An array of trimmed lines.
     */
    public static function breakString($string)
    {
        $parts = explode("\n", $string);

        foreach ($parts as &$part) {
            $part = rtrim($part);
            $part = ltrim($part);
        }

        return $parts;
    }

    /**
     * Checks whether a string has been modified by a callback function.
     *
     * @param string $target Input string.
     * @param \Closure $callback Function that processes the string.
     * @return bool True if the string was modified, false otherwise.
     */
    public static function strModified($target, Closure $callback)
    {
        $modified = $callback($target);

        return md5($target) !== md5($modified);
    }

    /**
     * Generates a serial number in the format XXXXX-XXXXX-XXXXX-XXXXX.
     * The format, length, and portion size can be customized.
     *
     * @param string $id Base string to generate the serial from.
     * @param bool $unique Whether the output must always be unique.
     * @param bool $upper Whether the output should be in uppercase.
     * @param int $length Total length of the serial (excluding dashes).
     * @param int $portion Length of each portion separated by dashes.
     * @return string Generated serial number.
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
     * Normalizes a database column name by converting underscores to spaces
     * and capitalizing each word.
     *
     * @param string $string Column name to normalize (e.g., "first_name").
     * @return string Normalized column name (e.g., "First Name").
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
     * Generates a unique string using the current time and random values.
     *
     * @return string A unique MD5-hashed string.
     */
    public static function uniqueString()
    {
        $id = uniqid(microtime() . rand(), true);
        return md5($id);
    }

    /**
     * Generates a random string of a fixed length using a specified character set.
     *
     * Supported dictionary methods:
     *  - "alpha"   : letters only (a-z, A-Z)
     *  - "alnum"   : letters and numbers (default)
     *  - "numeric" : numbers only (0-9)
     *
     * @param int $length Length of the random string to generate.
     * @param string $method Dictionary method to use ("alpha", "alnum", "numeric").
     * @throws \UnexpectedValueException If an unsupported dictionary method is provided.
     * @return string The generated random string.
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
     * Returns the starting and ending positions of all occurrences of a substring in a string.
     *
     * @param string $haystack The string to search in.
     * @param string $needle The substring to search for.
     * @return array An associative array where keys are start positions and values are end positions.
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
     * Trims a string to a maximum length and appends a suffix if truncated.
     *
     * @param string $string The input string to trim.
     * @param int $maxLen Maximum allowed length of the string.
     * @param string $after Suffix to append if the string is truncated (default: ' .... ').
     * @return string The trimmed string with the suffix if truncated, otherwise the original string.
     */
    public static function trim($string, $maxLen, $after = ' .... ')
    {
        $encoding = 'UTF-8';

        if (mb_strlen($string, $encoding) > $maxLen) {
            return mb_substr($string, 0, $maxLen, $encoding) . $after;
        } else {
            return $string;
        }
    }

    /**
     * Generates a URL-friendly slug from a string.
     *
     * @param string $string The input string to convert to a slug.
     * @param bool $romanize Whether to romanize non-Latin characters (default: true).
     * @return string The generated slug.
     */
    public static function sluggify($string, $romanize = true)
    {
        $generator = new SlugGenerator($romanize);
        return $generator->generate($string);
    }

    /**
     * Converts a string with non-Latin characters to a Latin (romanized) representation.
     *
     * @param string $string The input string to romanize.
     * @return string The romanized string.
     */
    public static function romanize($string)
    {
        return ForeignChars::romanize($string);
    }

    /**
     * Converts a string to snake_case.
     *
     * Examples:
     *  - "Hello World"  → "hello_world"
     *  - "camelCase"    → "camel_case"
     *
     * @param string $target The string to convert.
     * @return string The snake_case version of the string.
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
     * Converts a string to StudlyCase (each word capitalized and concatenated).
     *
     * Examples:
     *  - "hello world"   → "HelloWorld"
     *  - "my_variable"   → "MyVariable"
     *
     * @param string $input The string to convert.
     * @return string The StudlyCase version of the string.
     */
    public static function studly($input)
    {
        $input = mb_convert_case($input, \MB_CASE_TITLE, 'UTF-8');
        $input = str_replace(array('-', '_'), ' ', $input);
        $input = str_replace(' ', '', $input);

        return $input;
    }

    /**
     * Splits a text into sentences using common sentence delimiters.
     *
     * Default delimiters are: '.', '!', '?' and optionally a custom carriage return.
     *
     * @param string $text The input text to split.
     * @param string $carriage Optional carriage return character to detect new lines (default: "\r").
     * @return array An array of sentences.
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
     * Splits a string into an array using multiple delimiters.
     *
     * Can optionally keep the delimiters in the resulting array.
     *
     * @param string $string The input string to split.
     * @param array $delimiters An array of delimiters to use.
     * @param bool $keepDelimiters Whether to keep the delimiters in the output (default: true).
     * @return array An array of split string segments.
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

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validation;

/**
 * Handles dot notation array traversal, recursive wildcard expansion, and bracket transformations.
 *
 * @package Krystal\Validation
 */
final class PathResolver
{
    /**
     * Extracts a deeply nested value from an array using a resolved dot notation path.
     *
     * @param array $data The source array containing multi-dimensional inputs
     * @param string $path The absolute concrete dot notation path string
     * @return mixed|null The discovered value found at the path, or null if missing
     */
    public static function getNestedValue(array $data, string $path)
    {
        $segments = explode('.', $path);
        $current = $data;

        foreach ($segments as $segment) {
            if (!is_array($current) || !array_key_exists($segment, $current)) {
                return null;
            }
            $current = $current[$segment];
        }

        return $current;
    }

    /**
     * Resolves a wildcard notation pattern into a collection of absolute runtime data paths.
     *
     * @param array $data The array source to analyze against the wildcard pattern
     * @param string $pattern The dot notation path pattern containing wildcard markers
     * @return array A list of matching absolute concrete paths found within the array structure
     */
    public static function expandWildcards(array $data, string $pattern): array
    {
        $segments = explode('.', $pattern);
        $paths = [];
        
        self::resolveWildcardsRecursive($data, $segments, [], $paths);
        
        return $paths;
    }

    /**
     * Transforms an internal dot notation data path back to an HTML form field bracket notation name.
     *
     * @param string $dotPath The absolute concrete internal data path string
     * @return string The formatted HTML input name representation string
     */
    public static function toBracketNotation(string $dotPath): string
    {
        $segments = explode('.', $dotPath);
        $root = array_shift($segments);

        if (empty($segments)) {
            return $root;
        }

        return $root . '[' . implode('][', $segments) . ']';
    }

    /**
     * Traverses input array levels recursively to match structural branches against active pattern tokens.
     *
     * @param mixed $currentData The dynamic data fragment under active structural analysis
     * @param array $segments The remaining unparsed sequence of path pattern filter strings
     * @param array $compiled The matching path segments compiled so far down the current branch
     * @param array $paths Shared collector keeping track of fully resolved absolute paths
     * @return void
     */
    private static function resolveWildcardsRecursive($currentData, array $segments, array $compiled, array &$paths)
    {
        if (empty($segments)) {
            $paths[] = implode('.', $compiled);
            return;
        }

        $segment = array_shift($segments);

        if ($segment === '*') {
            if (is_array($currentData)) {
                foreach (array_keys($currentData) as $key) {
                    $nextCompiled = $compiled;
                    $nextCompiled[] = $key;
                    self::resolveWildcardsRecursive($currentData[$key], $segments, $nextCompiled, $paths);
                }
            }
            return;
        }

        if (is_array($currentData) && array_key_exists($segment, $currentData)) {
            $compiled[] = $segment;
            self::resolveWildcardsRecursive($currentData[$segment], $segments, $compiled, $paths);
        } elseif (empty($segments)) {
            $compiled[] = $segment;
            $paths[] = implode('.', $compiled);
        }
    }
}

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Autoloader;

/* The autoloader is not ready yet*/
require_once(__DIR__ . '/AbstractSplLoader.php');

/**
 * PSR-4 autoloader which can be used in addition to PSR-0
 * This autoloader is based on this implementation:
 * 
 * http://www.php-fig.org/psr/psr-4/
 */
final class PSR4 extends AbstractSplLoader
{
    /**
     * An associative array where the key is a namespace prefix and the value
     * is an array of base directories for classes in that namespace.
     *
     * @var array
     */
    private $prefixes = array();

    /**
     * Adds a collection to the stack
     * 
     * @param array $namespaces
     * @return void
     */
    public function addNamespaces(array $namespaces)
    {
        foreach ($namespaces as $prefix => $baseDir) {
            $this->addNamespace($prefix, $baseDir);
        }
    }

    /**
     * Adds a base directory for a namespace prefix
     *
     * @param string $prefix The namespace prefix
     * @param string $baseDir A base directory for class files in the namespace
     * @param bool $prepend If true, prepend the base directory to the stack instead of appending it
     * @return void
     */
    public function addNamespace($prefix, $baseDir, $prepend = false)
    {
        // normalize namespace prefix
        $prefix = trim($prefix, '\\') . '\\';

        // normalize the base directory with a trailing separator
        $baseDir = rtrim($baseDir, \DIRECTORY_SEPARATOR) . '/';

        // initialize the namespace prefix array
        if (isset($this->prefixes[$prefix]) === false) {
            $this->prefixes[$prefix] = array();
        }

        // retain the base directory for the namespace prefix
        if ($prepend) {
            array_unshift($this->prefixes[$prefix], $baseDir);
        } else {
            array_push($this->prefixes[$prefix], $baseDir);
        }
    }

    /**
     * Loads the class file for a given class name
     *
     * @param string $class The fully-qualified class name
     * @return mixed The mapped file name on success, or boolean false on failure
     */
    public function loadClass($class)
    {
        // the current namespace prefix
        $prefix = $class;

        // work backwards through the namespace names of the fully-qualified class name to find a mapped file name
        while (false !== $pos = strrpos($prefix, '\\')) {
            // retain the trailing namespace separator in the prefix
            $prefix = substr($class, 0, $pos + 1);

            // the rest is the relative class name
            $relativeClass = substr($class, $pos + 1);

            // try to load a mapped file for the prefix and relative class
            $mappedFile = $this->loadMappedFile($prefix, $relativeClass);

            if ($mappedFile) {
                return $mappedFile;
            }

            // remove the trailing namespace separator for the next iteration of strrpos()
            $prefix = rtrim($prefix, '\\');   
        }

        // never found a mapped file
        return false;
    }

    /**
     * Load the mapped file for a namespace prefix and relative class.
     * 
     * @param string $prefix The namespace prefix
     * @param string $relativeClass The relative class name
     * @return mixed Boolean false if no mapped file can be loaded, or the name of the mapped file that was loaded
     */
    private function loadMappedFile($prefix, $relativeClass)
    {
        // are there any base directories for this namespace prefix?
        if (isset($this->prefixes[$prefix]) === false) {
            return false;
        }

        foreach ($this->prefixes[$prefix] as $base_dir) {
            // replace the namespace prefix with the base directory,
            // replace namespace separators with directory separators
            // in the relative class name, append with .php
            $file = $base_dir . str_replace('\\', '/', $relativeClass) .self::EXTENSTION;

            // if the mapped file exists, require it
            if ($this->includeClass($file)) {
                // yes, we're done
                return $file;
            }
        }

        // never found it
        return false;
    }
}

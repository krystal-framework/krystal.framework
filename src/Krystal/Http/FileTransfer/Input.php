<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Http\FileTransfer;

use RecursiveIteratorIterator;
use RecursiveArrayIterator;
use RuntimeException;
use Krystal\Stdlib\ArrayUtils;

final class Input implements InputInterface
{
    /**
     * The local reference to $_FILES superglobal
     * 
     * @var array
     */
    private $files = array();

    /**
     * State initialization
     * 
     * @param array $files That must be $_FILES superglobal in its initial raw structure
     * @return void
     */
    public function __construct(array $files)
    {
        $this->files = $this->remapFiles($files);
    }

    /**
     * Checks whether named input field is empty. If $name is null, then checks the whole array
     * 
     * @param string $name Optionally filtered by name
     * @throws \RuntimeException if $name isn't valid field
     * @return boolean
     */
    public function hasFiles($name = null)
    {
        try {
            $files = $this->getFiles($name);
        } catch (RuntimeException $e) {
            return false;
        }

        if ($name === null) {
            foreach ($files as $name => $file) {
                if (empty($file)) {
                    return false;
                }
            }

            return true;
        } else {
            return !ArrayUtils::hasAllArrayValues($files);
        }
    }

    /**
     * Return all files. Optionally filtered by named input
     * 
     * @param string $name Name filter
     * @throws \RuntimeException if $name isn't valid field
     * @return object
     */
    public function getFiles($name = null)
    {
        if ($name !== null) {
            if (!array_key_exists($name, $this->files)) {
                throw new RuntimeException(sprintf(
                    'Attempted to access non-existing field %s', $name
                ));
            }

            return $this->hydrateAll($this->files[$name]);
        } else {
            return $this->hydrateAll($this->files);
        }
    }

    /**
     * Recursively hydrate array entires skipping empty files
     * 
     * @param array $files Remapped array
     * @return array
     */
    private function hydrateAll(array $files)
    {
        foreach ($files as $name => $file) {
            // Recursive protection
            if (!is_array($file)) {
                continue;
            }

            foreach ($file as $key => $value) {
                if (is_array($value)) {
                    // Recursive call
                    $files[$name] = call_user_func(array($this, __FUNCTION__), $files[$name]);
                } else {
                    $files[$name] = $this->hydrate($file);
                }
            }
        }

        return $files;
    }

    /**
     * Hydrates a single file
     * 
     * @param array $file
     * @return mixed
     */
    private function hydrate(array $file)
    {
        $entity = new FileEntity();
        $entity->setType($file['type'])
               ->setName($file['name'])
               ->setTmpName($file['tmp_name'])
               ->setSize($file['size'])
               ->setError($file['error']);

        return $entity;
    }

    /**
     * Remove broken ones from target array
     * A broken file is empty one
     * 
     * @param array $files Remapped files array
     * @return array
     */
    private function removeBrokenFiles(array $files)
    {
        // Recursive logic to replace broken arrays with empty ones (which to be removed later)
        if (isset($files['error'])) {
            return $files['error'] == 4 ? array() : $files;
        }

        foreach ($files as $key => $value) {
            // Recursive call
            $files[$key] = call_user_func(array($this, __FUNCTION__), $value);
        }

        // Now remove empty arrays
        return array_filter($files);
    }

    /**
     * Remaps superglogal $_FILES array
     * Taken from here: http://php.net/manual/en/reserved.variables.files.php#118294
     * 
     * @param array $input Raw $_FILES superglobal
     * @return array
     */
    private function remapFiles(array $input)
    {
        $output = array();

        foreach ($input as $name => $array) {
            foreach ($array as $field => $value) {
                $pointer = &$output[$name];
                if (!is_array($value)) {
                    $pointer[$field] = $value;
                    continue;
                }

                $stack = array(&$pointer);
                $iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($value), RecursiveIteratorIterator::SELF_FIRST);

                foreach ($iterator as $key => $value) {
                    array_splice($stack, $iterator->getDepth() + 1);
                    $pointer = &$stack[count($stack) - 1];
                    $pointer = &$pointer[$key];
                    $stack[] = &$pointer;

                    if (!$iterator->hasChildren()) {
                        $pointer[$field] = $value;
                    }
                }
            }
        }

        // Remove broken files as well
        return $this->removeBrokenFiles($output);
    }
}

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\View;

use LogicException;

final class PartialBag implements PartialBagInterface
{
    /**
     * Registered partial directories
     * 
     * @var array
     */
    private $partialDirs = array();

    /**
     * Static partials
     * 
     * @var array
     */
    private $staticPartials = array();

    /**
     * Attempts to return partial file path
     * 
     * @param string $name Partial name
     * @throws \LogicException If can't find partials file by its name
     * @return string
     */
    public function getPartialFile($name)
    {
        $file = $this->findPartialFile($name);

        if (is_file($file)) {
            return $file;

        } else if ($this->hasStaticPartial($name)) {
            return $this->getStaticFile($name);

        } else {
            throw new LogicException(sprintf('Could not find a registered partial called %s', $name));
        }
    }

    /**
     * Adds new static partial to collection
     * 
     * @param string $name
     * @param string $baseDir
     * @throws \LogicException if wrong data supplied
     * @return \Krystal\Application\View\PartialBag
     */
    public function addStaticPartial($baseDir, $name)
    {
        $file = $this->createPartialPath($baseDir, $name);

        if (!is_file($file)) {
            throw new LogicException(sprintf('Invalid base directory or file name provided "%s"', $file));
        }

        $this->staticPartials[$name] = $file;
        return $this;
    }

    /**
     * Adds a collection of static partials
     * 
     * @param array $collection
     * @return \Krystal\Application\View\PartialBag
     */
    public function addStaticPartials(array $collection)
    {
        foreach ($collection as $baseDir => $name) {
            $this->addStaticPartial($baseDir, $name);
        }

        return $this;
    }

    /**
     * Appends partial directory
     * 
     * @param string $dir
     * @return \Krystal\Application\View\PartialBag
     */
    public function addPartialDir($dir)
    {
        array_push($this->partialDirs, $dir);
        return $this;
    }

    /**
     * Appends several directories
     * 
     * @param array $dirs
     * @return \Krystal\Application\View\PartialBag
     */
    public function addPartialDirs(array $dirs)
    {
        foreach ($dirs as $dir) {
            $this->addPartialDir($dir);
        }

        return $this;
    }

    /**
     * Tries to find a partial within registered directories
     * 
     * @param string $name Partial name
     * @return mixed
     */
    private function findPartialFile($name)
    {
        foreach ($this->partialDirs as $dir) {
            $file = $this->createPartialPath($dir, $name);

            if (is_file($file)) {
                return $file;
            }
        }

        return false;
    }

    /**
     * Returns a path with base directory
     * 
     * @param string $baseDir
     * @param string $name
     * @return string
     */
    private function createPartialPath($baseDir, $name)
    {
        return sprintf('%s/%s.%s', $baseDir, $name, ViewManager::TEMPLATE_PARAM_EXTENSION);
    }

    /**
     * Checks whether static partial has been added before
     * 
     * @param string $name
     * @return boolean
     */
    private function hasStaticPartial($name)
    {
        $names = array_keys($this->staticPartials);
        return in_array($name, $names);
    }

    /**
     * Returns path to a static file
     * 
     * @param string $name Block's name
     * @return string
     */
    private function getStaticFile($name)
    {
        return $this->staticPartials[$name];
    }
}

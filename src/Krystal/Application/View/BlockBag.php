<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\View;

use LogicException;

final class BlockBag implements BlockBagInterface
{
    /**
     * Available block directories
     * 
     * @var string
     */
    private $blockDir;

    /**
     * Static blocks
     * 
     * @var array
     */
    private $staticBlocks = array();

    /**
     * Attempts to return block's file path
     * 
     * @param string $name Block's name
     * @throws \LogicException If can't find a block's file by its name
     * @return string
     */
    public function getBlockFile($name)
    {
        $file = $this->createBlockPath($this->getBlocksDir(), $name);

        if (is_file($file)) {
            return $file;

        } else if ($this->hasStaticBlock($name)) {
            return $this->getStaticFile($name);

        } else {
            throw new LogicException(sprintf('Could not find a registered block called %s', $name));
        }
    }

    /**
     * Returns a path with base directory
     * 
     * @param string $baseDir
     * @param string $name
     * @return string
     */
    private function createBlockPath($baseDir, $name)
    {
        return sprintf('%s/%s.%s', $baseDir, $name, ViewManager::TEMPLATE_PARAM_EXTENSION);
    }

    /**
     * Adds new static block to collection
     * 
     * @param string $name
     * @param string $baseDir
     * @throws \LogicException if wrong data supplied
     * @return \Krystal\Application\View\BlockBag
     */
    public function addStaticBlock($baseDir, $name)
    {
        $file = $this->createBlockPath($baseDir, $name);

        if (!is_file($file)) {
            throw new LogicException(sprintf('Invalid base directory or file name provided "%s"', $file));
        }

        $this->staticBlocks[$name] = $file;
        return $this;
    }

    /**
     * Adds a collection of static blocks
     * 
     * @param array $collection
     * @return \Krystal\Application\View\BlockBag
     */
    public function addStaticBlocks(array $collection)
    {
        foreach ($collection as $baseDir => $name) {
            $this->addStaticBlock($baseDir, $name);
        }

        return $this;
    }

    /**
     * Checks whether static block has been added before
     * 
     * @param string $name
     * @return boolean
     */
    private function hasStaticBlock($name)
    {
        $names = array_keys($this->staticBlocks);
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
        return $this->staticBlocks[$name];
    }

    /**
     * Returns block directory path
     * 
     * @return string
     */
    public function getBlocksDir()
    {
        return $this->blockDir;
    }

    /**
     * Defines block directory path
     * 
     * @param string $blockDir
     * @return \Krystal\Application\View\BlockBag
     */
    public function setBlocksDir($blockDir)
    {
        $this->blockDir = $blockDir;
        return $this;
    }
}

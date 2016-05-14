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

interface BlockBagInterface
{
    /**
     * Attempts to return block's file path
     * 
     * @param string $name Block's name
     * @throws \LogicException If can't find a block's file by its name
     * @return string
     */
    public function getBlockFile($name);

    /**
     * Adds new static block to collection
     * 
     * @param string $name
     * @param string $baseDir
     * @throws \LogicException if wrong data supplied
     * @return \Krystal\Application\View\BlockBag
     */
    public function addStaticBlock($baseDir, $name);

    /**
     * Adds a collection of static blocks
     * 
     * @param array $collection
     * @return \Krystal\Application\View\BlockBag
     */
    public function addStaticBlocks(array $collection);

    /**
     * Appends block directory
     * 
     * @param string $dir
     * @return \Krystal\Application\View\BlockBag
     */
    public function addBlockDir($dir);

    /**
     * Appends several directories
     * 
     * @param array $dirs
     * @return \Krystal\Application\View\BlockBag
     */
    public function addBlockDirs(array $dirs);
}

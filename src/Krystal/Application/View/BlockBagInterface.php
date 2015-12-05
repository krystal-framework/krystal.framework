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
     * Returns block directory path
     * 
     * @return string
     */
    public function getBlocksDir();

    /**
     * Defines block directory path
     * 
     * @param string $blockDir
     * @return \Krystal\Application\View\BlockBag
     */
    public function setBlocksDir($blockDir);
}

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

interface PartialBagInterface
{
    /**
     * Attempts to return partial file path
     * 
     * @param string $name Partial name
     * @throws \LogicException If can't find a block's file by its name
     * @return string
     */
    public function getPartialFile($name);

    /**
     * Adds new static partial to collection
     * 
     * @param string $name
     * @param string $baseDir
     * @throws \LogicException if wrong data supplied
     * @return \Krystal\Application\View\PartialBag
     */
    public function addStaticPartial($baseDir, $name);

    /**
     * Adds a collection of static partials
     * 
     * @param array $collection
     * @return \Krystal\Application\View\PartialBag
     */
    public function addStaticPartials(array $collection);

    /**
     * Appends partial directory
     * 
     * @param string $dir
     * @return \Krystal\Application\View\PartialBag
     */
    public function addPartialDir($dir);

    /**
     * Appends several directories
     * 
     * @param array $dirs
     * @return \Krystal\Application\View\PartialBag
     */
    public function addPartialDirs(array $dirs);
}

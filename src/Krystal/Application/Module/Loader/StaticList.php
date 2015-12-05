<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\Module\Loader;

final class StaticList implements LoaderInterface
{
    /**
     * Current modules
     * 
     * @var array
     */
    private $modules = array();

    /**
     * State initialization
     * 
     * @param array $modules
     * @return void
     */
    public function __construct(array $modules)
    {
        $this->modules = $modules;
    }

    /**
     * {@inheritDoc}
     */
    public function getModules()
    {
        return $this->modules;
    }
}

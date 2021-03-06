<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Db;

use Krystal\Paginate\PaginatorInterface;

/* All mapper factories must implement this interface regarding storage engine */
interface MapperFactoryInterface
{
    /**
     * Builds a mapper
     * 
     * @param string $namespace PSR-0 compliant mapper
     * @return void
     */
    public function build($namespace);
}

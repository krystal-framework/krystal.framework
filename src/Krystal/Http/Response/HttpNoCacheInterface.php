<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Http\Response;

interface HttpNoCacheInterface
{
    /**
     * Appends required headers to disable HTTP cache
     * 
     * @return void
     */
    public function configure();
}

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Db\Sql;

interface RawBindingInterface
{
    /**
     * Returns prepared value
     * 
     * @return mixed
     */
    public function getTarget();
}

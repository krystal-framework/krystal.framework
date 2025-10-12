<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Cache\Sql;

interface ConstProviderInterface
{
    const CACHE_PARAM_KEY = 'key';
    const CACHE_PARAM_VALUE = 'value';
    const CACHE_PARAM_TTL = 'ttl';
    const CACHE_PARAM_CREATED_ON = 'created_on';
}

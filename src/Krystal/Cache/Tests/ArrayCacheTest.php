<?php

namespace Krystal\Cache\Tests;

use Krystal\Cache\FileEngine\ArrayCache;

class ArrayCacheTest extends \PHPUnit_Framework_TestCase
{
    private $arrayCache;

    public function setUp()
    {
        $this->arrayCache = new ArrayCache();
        $this->arrayCache->setData(array(
            'name' => array(
                ArrayCache::CACHE_PARAM_VALUE => 'Jack',
                ArrayCache::CACHE_PARAM_TTL => 10,
                ArrayCache::CACHE_PARAM_CREATED => time(),
            ),
            'age' => array(
                ArrayCache::CACHE_PARAM_VALUE => 30,
                ArrayCache::CACHE_PARAM_TTL => 10,
                ArrayCache::CACHE_PARAM_CREATED => time(),
            ),
            'location' => array(
                ArrayCache::CACHE_PARAM_VALUE => 'Palo Alto',
                ArrayCache::CACHE_PARAM_TTL => 10,
                ArrayCache::CACHE_PARAM_CREATED => time(),
            )
        ));
    }
}

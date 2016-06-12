<?php

namespace Krystal\Cache\Tests;

use Krystal\Cache\FileEngine\ArrayCache;

class ArrayCacheTest extends \PHPUnit_Framework_TestCase
{
    private $arrayCache;

    public function setUp()
    {
        $this->arrayCache = new ArrayCache();
        $this->arrayCache->set('name', 'Jack', 10, time())
                         ->set('age', 30, 10, time())
                         ->set('location', 'Palo Alto', 10, time());
    }

    public function testCanBeIncremented()
    {
        $this->arrayCache->increment('age', 1);
        $age = $this->arrayCache->getValueByKey('age', false);

        $this->assertEquals($age, 31);
    }

    public function testCanBeDecremented()
    {
        $this->arrayCache->decrement('age', 1);
        $age = $this->arrayCache->getValueByKey('age', false);

        $this->assertEquals($age, 29);
    }

    public function testCanBeCleared()
    {
        $this->arrayCache->clear();
        $this->assertTrue($this->arrayCache->isEmpty());
    }
}

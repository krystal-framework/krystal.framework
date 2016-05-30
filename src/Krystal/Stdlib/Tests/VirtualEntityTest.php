<?php

namespace Krystal\Stdlib\Tests;

use Krystal\Stdlib\VirtualEntity;

class VirtualEntityTest extends \PHPUnit_Framework_TestCase
{
    public function testCanSetAndGet()
    {
        $entity = new VirtualEntity();
        $entity->setAge(24);

        $this->assertEquals($entity->getAge(), 24);
    }
}

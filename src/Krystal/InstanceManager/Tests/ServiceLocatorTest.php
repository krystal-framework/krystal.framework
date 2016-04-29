<?php

namespace Krystal\InstanceManager\Tests;

use Krystal\InstanceManager\ServiceLocator;

class ServiceLocatorTest extends \PHPUnit_Framework_TestCase
{
    public function testCanRegisterNewService()
    {
        $name = 'std';

        $sl = new ServiceLocator();
        $sl->register($name, new \stdclass());

        $this->assertTrue($sl->has($name));
    }

    public function testCanRemoveService()
    {
        $name = 'std';

        $sl = new ServiceLocator();
        $sl->register($name, new \stdclass());
        $sl->remove($name);

        $this->assertFalse($sl->has($name));
    }
}

<?php

namespace Krystal\Event\Tests;

use Krystal\Event\EventManager;

class EventManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testCanAttachAndExecute()
    {
        $em = new EventManager();
        $em->attach('foo', function(){
            return 'bar';
        });

        $this->assertEquals($em->trigger('foo'), 'bar');
    }

    public function testCanAttachAndDetach()
    {
        $event = 'foo';

        $em = new EventManager();
        $em->attach($event, function(){
            return 'bar';
        });

        $em->detach($event);

        $this->assertFalse($em->has($event));
    }

    public function testCanDetachAll()
    {
        $em = new EventManager();
        $em->attachMany(array(
            'x' => function(){},
            'y' => function(){}
        ));

        $em->detachAll();

        $this->assertEquals($em->countAll(), 0);
    }
}

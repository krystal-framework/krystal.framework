<?php

namespace Krystal\ParamBag\ParamBag\Tests;

use Krystal\ParamBag\ParamBag;

class ParamBagTest extends \PHPUnit_Framework_TestCase
{
    private $paramBag;
    
    public function setUp()
    {
        $this->paramBag = new ParamBag(array(
            'foo' => 'bar',
            'x' => 'y',
            'empty' => null
        ));
    }

    public function testDefinedKeyExists()
    {
        $this->assertTrue($this->paramBag->has('foo'));
    }

    public function testCanSetNewPair()
    {
        $this->paramBag->set('new', 'value');
        $this->assertTrue($this->paramBag->has('new'));
    }

    public function testCanReturnDefinedKey()
    {
        $this->assertEquals($this->paramBag->get('foo'), 'bar');
    }
}

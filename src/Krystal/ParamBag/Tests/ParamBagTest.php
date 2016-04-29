<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

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
        $this->assertTrue($this->paramBag->exists('foo'));
    }
    
    public function testCanSetNewPair()
    {
        $this->paramBag->set('new', 'value');
        $this->assertTrue($this->paramBag->exists('new'));
    }
    
    public function testCanReturnDefinedKey()
    {
        $this->assertEquals($this->paramBag->get('foo'), 'bar');
    }
}

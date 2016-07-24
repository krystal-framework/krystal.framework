<?php

namespace Krystal\Http\Tests;

use Krystal\Http\CookieBag;

class CookieBagTest extends \PHPUnit_Framework_TestCase
{
    private $cookieBag;

    public function setUp()
    {
        $this->cookieBag = new CookieBag($_COOKIE);
    }

    public function testCanWriteAndRead()
    {
        @$this->cookieBag->set('foo', 'bar');
        $this->assertEquals($this->cookieBag->get('foo'), 'bar');
    }

    public function testCanWriteAndRemove()
    {
       @$this->cookieBag->set('x', 'bar');
       @$this->cookieBag->remove('x');
       $this->assertFalse($this->cookieBag->has('x'));
    }

    public function testCanRemoveAll()
    {
       @$this->cookieBag->set('x', 'bar');
       @$this->cookieBag->set('y', 'bar');
       @$this->cookieBag->removeAll();

       $this->assertTrue($this->cookieBag->isEmpty());
    }
}

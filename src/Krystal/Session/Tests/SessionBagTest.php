<?php

namespace Krystal\Session\Tests;

use Krystal\Session\SessionBag;
use Krystal\Session\SessionValidator;
use Krystal\Http\CookieBag;

class SessionBagTest extends \PHPUnit_Framework_TestCase
{
    private $sessionBag;

    public function setUp()
    {
        $cookieBag = new CookieBag($_COOKIE);
        $validator = new SessionValidator($_SERVER);

        $this->sessionBag = new SessionBag($cookieBag, $validator);
    }

    public function testCanWriteOne()
    {
        $this->sessionBag->set('key', 'value');
        $this->assertTrue($this->sessionBag->has('key'));
    }

    public function testCanWriteMany()
    {
        $this->sessionBag->setMany(array(
            'key-1' => 'value-1',
            'key-2' => 'value-2'
        ));

        $this->assertTrue($this->sessionBag->has('key-1') && $this->sessionBag->has('key-2'));
    }
}

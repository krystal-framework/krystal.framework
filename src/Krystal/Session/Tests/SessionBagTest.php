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
}

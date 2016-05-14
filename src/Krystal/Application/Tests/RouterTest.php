<?php

namespace Krystal\Application\Tests;

use Krystal\Application\Route\Router;
use Krystal\Application\Route\RouteMatch;

class RouterTest extends \PHPUnit_Framework_TestCase
{
    public function testCanMatchStaticSegment()
    {
        $router = new Router();
        $result = $router->match('/about-us', array(
            '/home',
            '/about-us'
        ));

    }
}

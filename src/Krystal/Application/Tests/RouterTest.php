<?php

namespace Krystal\Application\Tests;

use Krystal\Application\Route\Router;
use Krystal\Application\Route\RouteMatch;

class RouterTest extends \PHPUnit_Framework_TestCase
{
    public function testCanMatchStaticSegment()
    {
        $router = new Router();
        $result = $router->match('/about-us', [
            '/home'     => ['controller' => 'Site@homeAction'],
            '/about-us' => ['controller' => 'Site@aboutAction'],
        ]);

        $this->assertTrue($result instanceof RouteMatch);
    }

    public function testCanCatchVars()
    {
        $router = new Router();
        $result = $router->match('/user/1', [
            '/user/(:var)' => ['controller' => 'Site@userAction'],
        ]);

        $this->assertTrue($result instanceof RouteMatch);
    }

    public function testCanMatchMiddleware()
    {
        $router = new Router();
        $result = $router->match('/admin', [
            '/admin' => [
                'controller' => 'Admin@indexAction',
                'middleware' => ['\Cms\Middleware\AuthMiddleware'],
            ],
        ]);

        $this->assertEquals(['\Cms\Middleware\AuthMiddleware'], $result->getMiddleware());
    }

    public function testReturnsFalseWhenNoMatch()
    {
        $router = new Router();
        $result = $router->match('/nonexistent', [
            '/home' => ['controller' => 'Site@homeAction'],
        ]);

        $this->assertFalse($result);
    }
}
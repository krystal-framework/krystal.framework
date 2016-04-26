<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Paginate\Tests;

use Krystal\Paginate\Paginator;

class PaginatorTest extends \PHPUnit_Framework_TestCase
{
    private $paginator;
    private $totalAmount = 30;
    private $perPage = 10;
    private $page = 2;

    public function setUp()
    {
        $paginator = new Paginator();
        $paginator->tweak($this->totalAmount, $this->perPage, $this->page);

        $this->paginator = $paginator;
    }

    public function tearDown()
    {
        unset($this->paginator);
    }

    public function testFirstPageIsAlwaysOne()
    {
        $this->assertEquals(1, $this->paginator->getFirstPage());
    }

    public function testIsCurrentPageTheSame()
    {
        $this->assertTrue($this->paginator->isCurrentPage($this->page));
    }

    public function testHasMoreThanOnePage()
    {
        $this->assertTrue($this->paginator->hasPages());
    }

    public function testHasNextPage()
    {
        $this->assertFalse($this->paginator->hasNextPage());
    }

    public function testHasPreviousPage()
    {
        $this->assertTrue($this->paginator->hasPreviousPage());
    }

    public function testTotalAmountIsTheSame()
    {
        $this->assertEquals($this->totalAmount, $this->paginator->getTotalAmount());
    }
}

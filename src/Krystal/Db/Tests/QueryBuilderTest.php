<?php

namespace Krystal\Db\Tests;

use Krystal\Db\Sql\QueryBuilder;

class QueryBuilderTest extends \PHPUnit_Framework_TestCase
{
    private $qb;

    public function setUp()
    {
        $this->qb = new QueryBuilder();
    }
}

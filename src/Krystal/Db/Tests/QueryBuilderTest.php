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

    private function verify($fragment)
    {
        $this->assertEquals($fragment, $this->qb->getQueryString());
    }

    public function testCanGenerateSelect()
    {
        $this->qb->select();
        $this->verify('SELECT ');
    }

    public function testCanGenerateDistinctSelect()
    {
        $this->qb->select(null, true);
        $this->verify('SELECT DISTINCT ');
    }

    public function testCanGenerateSelectWithColumns()
    {
        $this->qb->select(array('id', 'name'));
        $this->verify('SELECT id, name');
    }

    public function testCanGenerateColumnsWithAlias()
    {
        $this->qb->select(array(
            array('table.column' => 'alias'),
            'name'
        ));

        $this->verify('SELECT table.column AS `alias`, name');
    }

    public function testCanGenerateSelectFromTable()
    {
        $this->qb->select('*')
                 ->from('table');

        $this->verify('SELECT * FROM `table`');
    }

    public function testCanGenerateSelectFrom()
    {
        $this->qb->select('*')
                 ->from();

        $this->verify('SELECT * FROM ');
    }
}

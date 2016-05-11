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

    public function testCanGenerateInsert()
    {
        $this->qb->insert('table', array('name' => "'Dave'", 'age' => '24'));

        $this->verify("INSERT  INTO `table` (`name`, `age`) VALUES ('Dave', 24)");
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

    public function testCanGenerateSelectWithAvg()
    {
        $this->qb->select()
                 ->avg('users');

        $this->verify('SELECT  AVG(`users`) ');
    }

    public function testCanGenerateSelectWithMin()
    {
        $this->qb->select()
                 ->min('count');

        $this->verify('SELECT  MIN(`count`) ');
    }

    public function testCanGenerateSelectWithMax()
    {
        $this->qb->select()
                 ->max('count');

        $this->verify('SELECT  MAX(`count`) ');
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

    public function testCanGenerateWhereEquals()
    {
        $this->qb->select('*')
                 ->from('table')
                 ->whereEquals('id', '1');

        $this->verify('SELECT * FROM `table` WHERE `id` = 1 ');
    }

    public function testCanGenerateWhereNotEquals()
    {
        $this->qb->select('*')
                 ->from('table')
                 ->whereNotEquals('id', '1');

        $this->verify('SELECT * FROM `table` WHERE `id` != 1 ');
    }

    public function testCanGenerateWhereGreaterThan()
    {
        $this->qb->select('*')
                 ->from('table')
                 ->whereGreaterThan('count', '1');

        $this->verify('SELECT * FROM `table` WHERE `count` > 1 ');
    }

    public function testCanGenerateWhereLessThan()
    {
        $this->qb->select('*')
                 ->from('table')
                 ->whereLessThan('count', '1');

        $this->verify('SELECT * FROM `table` WHERE `count` < 1 ');
    }

    public function testCanGenerateDropTable()
    {
        $this->qb->dropTable('users', false);

        $this->verify('DROP TABLE `users`');
    }

    public function testCanGenerateDropTableIfExists()
    {
        $this->qb->dropTable('users');

        $this->verify('DROP TABLE IF EXISTS `users`');
    }
}

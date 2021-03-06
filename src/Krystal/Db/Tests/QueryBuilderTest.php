<?php

namespace Krystal\Db\Tests;

use Krystal\Db\Sql\QueryBuilder;
use Krystal\Db\Sql\RawSqlFragment;

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

    public function testOrderByCanAcceptRawFragment()
    {
        $this->qb->select('*')
                 ->from('table')
                 ->orderBy(array('name', new RawSqlFragment('IF (true, 1, 0)')));

        $this->verify('SELECT * FROM `table` ORDER BY `name`, IF (true, 1, 0)');
    }

    public function testCanGenerateWhereRandom()
    {
        // Build query
        $this->qb->select('*')
                 ->from('table')
                 ->whereRandom('id');

        $this->verify('SELECT * FROM `table` WHERE `id` >= FLOOR(1 + RAND() * (SELECT  MAX(`id`)  FROM `table`)) ');
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
        $this->verify('SELECT `id`, `name`');
    }

    public function testCanGenerateColumnsWithAlias()
    {
        $this->qb->select(array(
            'table.column' => 'alias',
            'name'
        ));

        $this->verify('SELECT table.column AS `alias`, `name`');
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

    public function testCanGenerateSelectWithCount()
    {
        $this->qb->select()
                 ->count('count');

        $this->verify('SELECT  COUNT(`count`) ');
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

    public function testCanGenerateWhereLike()
    {
        $this->qb->select('*')
                 ->from('table')
                 ->whereEquals('id', '1');

        $this->verify('SELECT * FROM `table` WHERE `id` = 1 ');
    }

    public function testCanGenerateWhereEquals()
    {
        $this->qb->select('*')
                 ->from('table')
                 ->whereLike('name', 'foo');

        $this->verify('SELECT * FROM `table` WHERE `name` LIKE foo ');
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

    public function testCanGenerateAndWhere()
    {
        $this->qb->select('*')
                 ->from('users')
                 ->whereEquals('user', "'Dave'")
                 ->andWhereEquals('age', "'24'");

        $this->verify("SELECT * FROM `users` WHERE `user` = 'Dave' AND `age` = '24' ");
    }

    public function testCanSaveSelectedTableName()
    {
        $this->qb->select('*')
                 ->from('users');

        $this->assertEquals('users', $this->qb->getSelectedTable());
    }

    public function testCanGenerateDropTable()
    {
        $this->qb->dropTable('users', false);

        $this->verify('DROP TABLE `users`;');
    }

    public function testCanGenerateDropManyTables()
    {
        $this->qb->dropTable(array('users', 'passwords'), false);

        $this->verify('DROP TABLE `users`, `passwords`;');
    }

    public function testCanGenerateDropTableIfExists()
    {
        $this->qb->dropTable('users');

        $this->verify('DROP TABLE IF EXISTS `users`;');
    }

    public function testCanGenerateIncrement()
    {
        $this->qb->increment('articles', 'views');

        $this->verify('UPDATE `articles` SET `views` = views + 1');
    }

    public function testCanGenerateDecrement()
    {
        $this->qb->decrement('articles', 'views');

        $this->verify('UPDATE `articles` SET `views` = views - 1');
    }
}

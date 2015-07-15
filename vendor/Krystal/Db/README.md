Database component
==================

This component provides a flexible tool to deal with SQL. It's not an ORM, but a tool that let's you build SQL queries quickly via beautiful APIs. This component can be used as a standalone library as well. To do so, you can simply register PSR-0 compliant autoloader, that points to Krystal's directory.

Goals
=====

 - Make database interactions purely via Data Mappers. Adhere to the Single-Responsibility Principle.
   No awkward Active Records with their ugly approach on table relations and bunch of their magic stuff (gosh!)

 - Make the component easy to use extend
 - Make API and usage as simple as possible. Users should not be bothered about internals, they only have to know how to call methods!
 - Force users to encapsulate all database interactions into their mappers

Features
========

 - Easy data filtering. This can be used for handling filters in data grids
 - Smart pagination
 - Relations
 - Query builder
 - Support for popular engines
 - Short-cuts for very common operations
 - Data mapper oriented 
 - Raw SQL support
 - Query logging

Why is it better than raw PDO and ORMs?
=================================

When writing queries via raw PDO, you end up with lot of duplications. For instance, binding placeholders everywhere and all the time and calling `prepare()` -> `fetch()` can be considered as code duplication. When it comes to building dynamic queries, it would get messy very quickly.

As for why it's better than ORMs, this is because it doesn't hide the SQL, but helps you to build it, so you can take advantage of particular database engine when needed. Still, there are shortcut-methods, that look similar to ORM's common methods.

Getting started
===============

To get started, you need to create some data mapper class, that extends `\Krystal\Sql\AbstractMapper`. Once you've done, you can start writing methods that abstract table access. When writing those methods, you would want to take advantage of the power of query builder component. For example, you first mapper might look like so:

    <?php
    
    namespace SomeNamespace;
    
    use Krystal\Sql\AbstractMapper;
    
    final class BookMapper extends AbstractMapper
    {
        private $table = 'some_table_name';
        
        public function fetchById($id)
        {
              return $this->db->select('*')
                              ->from($this->table)
                              ->whereEquals('id', $id)
                              ->query();
        }
        
        public function deleteById($id)
        {
               return $this->db->delete()
                               ->from($this->table)
                               ->whereEquals('id', $id)
                               ->execute();
        }
    }

As you noted, there's an instance, which is `db` that builds a query and then queries a database. Now let's take a look, what methods query build offers.

Building queries
=============

# select()

    \Krystal\Db\Sql\Db::select ( array|string = $type, $distinct = false)

This method builds `SELECT` query fragment. It may accept an array or a string. If you supply an array, then you have two options:

    $this->db->select(array('name', 'email'))

That would build a fragment like this: `SELECT name, email`, wrapping them in back-ticks also.

You can use array values to specify an alias to a column, like this:

    $this->db->select(array('foo' => 'bar'))

That would build a fragment which would look like as:

    SELECT foo as bar


And lastly, you can pass a string, like this

    $this->db->select('*')

That would build a fragment like this one:

`SELECT *`

If you pass a second argument as true, the it would append `DISTINCT` right after `SELECT`

# from()

    \Krystal\Db\Sql\Db::from($table = null)

This method specific a source to be used. It most cases that would be a table, but sometimes you might want to use several tables when bulding `UNION` queries. This method is usually gets called right after `select()`, like this:

    $this->db->select('*')
             ->from('some_table')

That would build a fragment like this:

    SELECT * FROM some_table

# where()

    \Krystal\Db\Sql\Db::where($column, $operator, $value, $filter = false)

This method simply appends `WHERE` clause and usually gets called right after `from()`. For instance, this call:

    $this->db->select('*')
             ->from('some_table')
             ->where('id', '=', '1')

Would build a query like this;

    SELECT * FROM some_table WHERE id = '1'

Lastly, there's a 4-th argument which is called `$filter`. If you set it to `true`, then `WHERE` clause is appended only case its `$value` it not empty.

There are several shortcut methods for `where()`, that substitute operators.

    whereLike($column, $value, $filter = false)
    whereNotEquals($column, $value, $filter = false)
    whereEquals($column, $value, $filter = false)
    whereLessThan($column, $value, $filter = false)
    whereLessThanOrEquals($column, $value, $filter = false)
    whereGreaterThan($column, $value, $filter = false)
    whereGreaterThanOrEquals($column, $value, $filter = false)


# andWhere()


    \Krystal\Db\Sql\Db::andWhere($column, $operator, $value, $filter = false)

This method appends `AND WHERE` fragment. It should not be used as a first `WHERE` clause, but should be used as a second. Just like this:

    $this->db->select('*')
             ->from('users')
             ->where('name', '=', 'John')
             ->andWhere('lastname', '=', 'Doe');

That would build the following query:

    SELECT * FROM users WHERE name = 'Jonh' AND WHERE lastname = 'Doe'

There are also several shortcut methods, that substitute an operator accordingly:

    andWhereLike($column, $value, $filter = false)
    andWhereEquals($column, $value, $filter = false)
    andNotWhereEquals($column, $value, $filter = false)
    andWhereLessThan($column, $value, $filter = false)
    andWhereGreaterThan($column, $value, $filter = false)
    andWhereEqualsOrGreaterThan($column, $value, $filter = false);
    andWhereEqualsOrLessThan($column, $value, $filter = false)


# orWhere()

    \Krystal\Db\Sql\Db::orWhere($column, $operator, $value, $filter = false)

This methods appends `OR WHERE` fragment. Just like `andWhere()` it should be used as a second `WHERE` clause. Like this:

    $this->db->select('*')
             ->from('users')
             ->where('name', '=', 'Dave')
             ->orWhere('name', '=', 'Jason')

This call will produce the following query:

    SELECT * FROM users WHERE name = 'Dave' OR WHERE name = 'Jason'

Just like two previous methods it has shortcut methods as well. Here they are:

    orWhereEquals($column, $value, $filter = false)
    orWhereNotEquals($column, $value, $filter = false)
    orWhereLike($column, $value, $filter = false)
    orWhereGreaterThanOrEquals($column, $value, $filter = false)
    orWhereLessThanOrEquals($column, $value, $filter = false)
    orWhereLessThan($column, $value, $filter = false)
    orWhereGreaterThan($column, $value, $filter = false)

# whereIn()

    \Krystal\Db\Sql\Db::whereIn($column, array $values, $filter = false)

This method appends `WHERE IN` fragment. It's used like this

    $this->db->select('*')
             ->from('products')
             ->whereIn('price', array(200, 300))

This will produce the following query:

    SELECT * FROM products WHERE price IN (200, 300)


# whereBetween()

    \Krystal\Db\Sql\Db::whereBetween($column, $a, $b, $filter = false)

This method appends `WHERE column BETWEEN value1 AND value2` clause. It's used like this:

    $this->db->select('*')
             ->from('products')
             ->whereBetween('price', 200, 500)

This call will produce the following query:

    SELECT * FROM products WHERE price BETWEEN 200 AND 500

# limit()

    \Krystal\Db\Sql\Db::limit($offset, $amount = null)

This method appends `LIMIT` fragment. Since `LIMIT` itself can be written in two ways, either with or without offset, you can omit second argument, which is about amount of records to be returned. 

An example,

    $this->db->select('*')
             ->from('products')
             ->limit(10);

This call will product the following query:

    SELECT * FROM products LIMIT 10

If you pass a second argument, then it would append its value after a comma. For example:

    $this->db->select('*')
             ->from('products')
             ->limit(0, 10);

Will produce:

    SELECT * FROM products LIMIT 0, 10

But wait!
In most cases, you don't need to use the `limit()`  to build pagination logic. There's a built-in method, which is called `paginate()` that does all pagination tweaks and calls `limit()` internally.

# groupBy()

    \Krystal\Db\Sql\Db::groupBy( array|string $target)

This method appends `GROUP BY` statement. As its argument it may accept either a string or an array of column names. Usually gets called right after singe or a bunch of `WHERE` statements.

For example, this call

$this->db->select('*')
               ->from('products')
               ->whereLessThan('price', 1000)
               ->groupBy('name')

Will generate the following query:

    SELECT * FROM products WHERE price < 1000 GROUP BY name


# orderBy()

    \Krystal\Db\Sql\Db::orderBy( array|string|\Krystal\Db\Sql\RawSqlFragmentInterface $type = null )

This method appends `ORDER BY` statement. As an argument it may accept an array of column names, a single column name, or an instance of `Krystal\Db\Sql\RawSqlFragmentInterface` that contains raw SQL fragment.

For example, this call:

    $this->db->select('*')
             ->from('products')
             ->orderBy('price')

Will generate the following query:

    SELECT * FROM products ORDER BY price

# desc()

    \Krystal\Db\Sql\Db::desc()


This method simply appends `DESC`. Typically it's used right after `orderBy()`. 

There's no `asc()` method since, it's default sorting method in almost all databases.


# having()

    \Krystal\Db\Sql\Db::having($function, $column, $operator, $value)

This methods generates `HAVING function(column) operator value` fragment. Usually it gets appended at the end of a query. 

For example, a call that has `having()` at the end:

       $this->db->
                ...
                ->having('count', 'id', '>', '100')

Will generate the following fragment:

    ... HAVING count(id) > 100

# union()

    \Krystal\Db\Sql\Db::union()

This method appends `UNION`. There's also one similar method `unionAll()`, that appends `UNION ALL`. Here's an example of its usage:

    $this->db->select('*')
             ->from('products')
             ->union()
             ->select('*')
             ->from('orders')

This will generate the following query:

    SELECT * FROM products UNION SELECT * FROM orders

# asAlias()

    \Krystal\Db\Sql\Db::asAlias()

Since `as` is a reserved word in PHP, this method is called `asAlias()`. It simply appends `AS` keyword to the existing stack. It can be used when building complex queries where aliases are required.


# delete()

    \Krystal\Db\Sql\Db::delete()

Appends `DELETE`. Typically used when building a query to perform removal of some rows. An example:

    $this->db->delete()
             ->from('users')
             ->whereEquals('id', '1')

This call will generate the following query:

    DELETE FROM users WHERE id = 1

# insert()

    \Krystal\Db\Sql\Db::insert($table, array $data, $ignore = false)

Appends `INSERT INTO table (...) VALUES (...)`. An example:

    $data = array(
      'name' => 'Dave',
      'age' => '23'
    );
    
    $this->db->insert('users', $data)

This will call will generate the following query:

    INSERT INTO users (name, age) VALUES ('Dave', '23')

There's a third argument `$ignore`, which tells the method if `IGNORE` keyword before the `INTO` should be appended or not. By default, it's false.

# update()

    \Krystal\Db\Sql\Db::update($table, array $data)

Appends `UPDATE table SET column = value....`. In most cases, right after the call, you would usually want to append `whereEquals()` An example:

    $data = array(
       'name' => 'Dave',
       'age' => '24' 
    );
    
    $this->db->update('users', $data)
             ->whereEquals('id', '1')

This call will generate the following query:

    UPDATE users SET name = 'Dave', age = '24' WHERE id = '1'


# increment() & decrement()

    \Krystal\Db\Sql\Db::increment($table, $column, $step = 1
    \Krystal\Db\Sql\Db::decrement($table, $column, $step = 1

This is just wrappers around `update()` that do generate increment and decrement queries for columns. An example:

    $this->db->increment('products', 'views')
             ->whereEquals('id', '1')

This will generate the following query:

    UPDATE products SET views = views + 1 WHERE id = '1'

The same  signature applies to `decrement()`, but it does `-` instead of `+`, as you might already guessed.

# JOINs

There are 4 methods that generate join queries. Usually the get called right after `from()` method. Here they are:

    innerJoin($table, $a, $b)
    leftJoin($table, $a, $b)
    rightJoin($table, $a, $b)
    fullJoin($table, $a, $b)
    

They all do append a fragment, which is`%TYPE% JOIN second_table ON first_table.column = second_table.column` , where `%TYPE%`  is just a type of join call.

For example, this call:

    $this->db->select('*')
             ->from('products')
             ->innerJoin('orders', 'products.order_id', 'orders.id')

Will generate the following query:

    SELECT * FROM products
    INNER JOIN orders
    ON products.order_id = orders.id;


# SQL functions

There are also several methods, that generate functions. Let's take a peek at them. Here they are:

    \Krystal\Db\Sql\Db::max($column, $alias = null)
    \Krystal\Db\Sql\Db::min($column, $alias = null)
    \Krystal\Db\Sql\Db::avg($column, $alias = null)
    \Krystal\Db\Sql\Db::sum($column, $alias = null)
    \Krystal\Db\Sql\Db::count($column, $alias = null)
    \Krystal\Db\Sql\Db::len($column, $alias = null)
    \Krystal\Db\Sql\Db::round($column, $decimals)
    \Krystal\Db\Sql\Db::rand()
    \Krystal\Db\Sql\Db::now()


As you might already guessed, they all do append `$function($column)` fragment. in case its second argument`$alias` isn't `null`, then it uses its name as an alias and generates `$function($column) AS $alias` instead.

Let's take one of them, and see how its typically used:

    $this->db->select()
             ->max('price', 'max_price')
             ->from('products')

This will generate the following query:

    SELECT MAX(price) AS max_price FROM products

# Executing queries

So far, you have learned only how to build queries. Once you've done building, your queries won't be executed in any way. 

There are two methods, that let you execute your queries, that you built to fetch a result-set:

    \Krystal\Db\Sql\Db::query($column = null)
    \Krystal\Db\Sql\Db::queryAll($column = null)

The `query()` fetches a single row, while `queryAll()` fetches all rows. The optional argument `$column` they have is about filtering result-set (a plain array) by a key.


And there's also one method to execute queries, that never return a result-set. Those queries are `INSERT`, `UPDATE` and `DELETE`.

    \Krystal\Db\Sql\Db::execute()

Let's take a look at examples:

    // Ex.1: Finding user's data by his associated id
    $this->db->select('*')
             ->from('users')
             ->whereEquals('id', '1')
             ->query(); // <- Execute the query and get a result-set
    
    // Ex.2: Removing user's row by associated id
    $this->db->delete()
             ->from('users')
             ->whereEquals('id', '1')
             ->execute(); // -> Execute the query without expecting a result-set in return

# Pagination

    \Krystal\Db\Sql\Db::paginate($page, $itemsPerPage)

Dropping a large result-set into small ones by pages is a very common task every developer faces. Krystal's DB component has a smart solution to this problem, which is simple `paginate()` method.

It should be always called before `queryAll()`. So, let's see how it works in action:

    $page = 1; // Current page
    $itemsPerPage = 5; // Per page count
    
    $books = $this->db->select('*')
                      ->from('books')
                      ->paginate($page, $itemsPerPage)
                      ->queryAll();

OK, now what?
Done, that's it!
Now you can simply call `getPaginator()` on a mapper, since `paginate()` method internally tweaks paginator behind the scenes. After all you you'd pass paginator's instance to a view from a service later.

# Shortcuts

A shortcut is a method that does some very common thing. Take as example these things:

-- You will be often finding a row by its associated id
-- You will be often removing a row by its associated id
-- You will be often inserting or removing records

And things like that.

To reduce the amount similar queries, Krystal's database component has shortcut methods. 

But wait, in order to start using them, you have to implement two methods in your mapper that would use shortcuts:

    string public static function getTableName()

It must return a name of a table you're going to deal with.

and 

    string protected function getPk()

It must return a name of primary key in your table. Often it's called `id`

Alright, now let's explore each shortcut method.


# findByPk()

    \Krystal\Db\Sql\AbstractMapper::findByPk($id)

Finds a row by its associated id, selecting all columns and returning an array. It's an equivalent to:

    $this->db->select('*')
             ->from(self::getTableName())
             ->whereEquals($this->getPk(), $id)
             ->query()

# findColumnByPk()

    \Krystal\Db\Sql\AbstractMapper::findColumnByPk($id, $column)

Finds column's value by provided PK's value. It's useful if you want to select only one column by associated id.

It's an equivalent to: 

	$this->db->select($column)
			 ->from(self::getTableName())
			 ->whereEquals($this->getPk(), $id)
			 ->query($column);

# getLastPk()

    \Krystal\Db\Sql\AbstractMapper::getLastPk()

Returns a maximal value of PK. It's an equivalent to: 

    $this->db->select()
    		 ->max($this->getPk(), 'last')
    		 ->from(self::getTableName())
    		 ->query('last')


# persist()

    \Krystal\Db\Sql\AbstractMapper::persist(array $data)

This method updates an existing record or inserts a new one. If it can find a PK's name in `$data` 's key which is not empty, it will update a record. Otherwise it will insert a new one.


# updateColumnByPk()

    \Krystal\Db\Sql\AbstractMapper::updateColumnByPk()($pk, $column, $value)

Updates a single column by associated id in a table. This is an equivalent to this call:

    $this->db->update(self::getTableName(), array($column => $value))
    		->whereEquals($this->getPk(), $pk)
    		->execute();


# incrementColumnByPk()


     \Krystal\Db\Sql\AbstractMapper::incrementColumnByPk($pk, $column, $step = 1)

Increments a value of a numeric column. This is an equivalent to:

    $this->db->increment(self::getTableName(), $column, $step)
    		->whereEquals($this->getPk(), $pk)
    		->execute();

# decrementColumnByPk()

    \Krystal\Db\Sql\AbstractMapper::decrementColumnByPk($pk, $column, $step = 1)

Decrements a value of a numeric column. This is an equivalent to:

    $this->db->decrement(self::getTableName(), $column, $step)
    		->whereEquals($this->getPk(), $pk)
    		->execute();


# Using raw PDO

Query builder can not totally abstract SQL language, especially since it might be DB-vendor specific. Therefore, you might encounter scenarios where you would want to write plain SQL queries.

To access raw PDO instance, just call `getPdo()`, like this:

    $pdo = $this->db->getPdo();


@TODO

 - Sections in doc: Non-pk specific shortcuts, connection, query logger & debug, relations
 * Need more SQL connectors
 * SQL Table Relations - Implement very common types at least
 * Methods in SQL\Qb for migrations

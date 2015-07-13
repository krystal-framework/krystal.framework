Database component
==================

This component provides a flexible abstraction over SQL engines. It's not an ORM, but a tool that let's you build SQL queries quickly via beautiful APIs. This component can be used as a standalone library as well. To do so, you can simply register PSR-0 compliant autoloader, that points to Krystal's directory.

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

Available query methods
====================

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
             ->whereIn('price', array('200', 300))

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

TODO
====

 * Need more SQL connectors
 * SQL Table Relations - Implement very common types at least
 * Methods in SQL\Qb for migrations



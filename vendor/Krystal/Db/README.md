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

    \Krystal\Db\Sql\Db::select ( array|string )

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

There are several shortcuts for `where()` operators.

    whereLike($column, $value, $filter = false)
    whereEquals($column, $value, $filter = false)
    whereLessThan($column, $value, $filter = false)
    whereLessThanOrEquals($column, $value, $filter = false)
    whereGreaterThan($column, $value, $filter = false)
    whereGreaterThanOrEquals($column, $value, $filter = false)


TODO
====

 * Need more SQL connectors
 * SQL Table Relations - Implement very common types at least
 * Methods in SQL\Qb for migrations








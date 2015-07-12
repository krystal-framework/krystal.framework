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


TODO
====

 * Need more SQL connectors
 * SQL Table Relations - Implement very common types at least
 * Methods in SQL\Qb for migrations


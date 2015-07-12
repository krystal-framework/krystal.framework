Database component
==================

This component provides a flexible abstraction over SQL engines. It's not an ORM, but a tool that let's you build SQL queries quickly via beautiful APIs.
To get started, please refer to its documentation.

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

This component can be used as a standalone library as well. To do so, you can simply register PSR-0 compliant autoloader, that points to Krystal's directory.



TODO
====

 * Need SQL more connectors
 * SQL Table Relations - Implement very common types at least
 * Methods in SQL\Qb for migrations

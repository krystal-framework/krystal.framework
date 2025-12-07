
Database component
==================

This component provides a flexible tool to deal with SQL. It's not an ORM, but a tool that let's you build SQL queries quickly via beautiful APIs.

Goals
=====

 - Force database interactions purely via Data Mappers. Adhere to the Single-Responsibility Principle.
 - Make the component easy to use extend.
 - Make API and usage as simple as possible.


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

Configuration
=============

The configuration data (typically located at `/config/app.php`) is located under `db` component. 

There are several database drivers, that you can define:

- mysql [required options: `host`, `dbname`, `username`, `password`]
- postgresql [required options: `host`, `dbname`, `user`, `password`]
- sqlite [required options: `path`]
- sqlserver [required options: `host`, `dbname`, `username`, `password`]

The configuration must contain driver name and its associated array with options. As an example, the configuration for MySQL on a local machine would look like so:

    'db' => array(
    	'mysql' => array(
    		'host' => 'localhost',
    		'dbname' => 'your_db_name',
    		'username' => 'root',
    		'password' => ''
    	)
    )


Getting started
===============

To get started, you need to create some data mapper class, that extends `\Krystal\Sql\AbstractMapper`. Once you've done, you can start writing methods that abstract table access. When writing those methods, you would want to take advantage of the power of query builder component. For example, you first mapper might look like so:

    <?php
    
    namespace SomeNamespace;
    
    use Krystal\Db\Sql\AbstractMapper;
    
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

As you noted, there's an instance, which is `db` that builds a query and then queries a database. Now let's take a look, what methods query builder offers out of the box for you.

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

This method specifies a source to be selected from. It most cases that is a table name, but sometimes you might want to use several tables when building `UNION` queries when building complex queries. This method is usually gets called right after `select()`, like this:

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

Will generate the following query:

    SELECT * FROM some_table WHERE id = '1'

Lastly, there's a 4-th argument which is called `$filter`. If you set it to `true`, then `WHERE` clause is appended only case its `$value` is not empty.

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

This method appends `AND WHERE` fragment. It should not be used as a first `WHERE` clause, but should be used for following. Just like this:

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

This method appends `WHERE IN` fragment. It's used like this:

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
In most cases, you don't need to use the `limit()` to build pagination logic. There's a built-in method, which is called `paginate()` that does all pagination tweaks and calls `limit()` internally.

# groupBy()

    \Krystal\Db\Sql\Db::groupBy( array|string $target)

This method appends `GROUP BY` statement. As its argument it may accept either a string or an array of column names. It usually gets called right after single or a bunch of `WHERE` statements.

For example, this call:

    $this->db->select('*')
                   ->from('products')
                   ->whereLessThan('price', 1000)
                   ->groupBy('name')

Will generate the following query:

    SELECT * FROM products WHERE price < 1000 GROUP BY name


# orderBy()

    \Krystal\Db\Sql\Db::orderBy( array|string|\Krystal\Db\Sql\RawSqlFragmentInterface $type = null )

This method appends `ORDER BY` statement. As an argument it may accept an array of column names or a single column name, or an instance of `Krystal\Db\Sql\RawSqlFragmentInterface` that contains raw SQL fragment.

For example, this call:

    $this->db->select('*')
             ->from('products')
             ->orderBy('price')

Will generate the following query:

    SELECT * FROM products ORDER BY price

You can also define sorting options for particular columns like this:

    $this->db->select('*')
             ->from('products')
             ->orderBy(array('price' => 'DESC', 'date' => 'DESC'))

This will generate the following query:

    SELECT * FROM products ORDER BY price DESC, date DESC

# desc()

    \Krystal\Db\Sql\Db::desc()


This method simply appends `DESC`. Typically it's used right after `orderBy()`. 


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

There's a third argument `$ignore`, which tells the method if `IGNORE` keyword before the `INTO` should be appended or not. By default, its `false`.

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

    \Krystal\Db\Sql\Db::increment($table, $column, $step = 1)
    \Krystal\Db\Sql\Db::decrement($table, $column, $step = 1)

This is just wrappers around `update()` that do generate increment and decrement queries for columns. An example:

    $this->db->increment('products', 'views')
             ->whereEquals('id', '1')

This will generate the following query:

    UPDATE products SET views = views + 1 WHERE id = '1'

The same signature applies to `decrement()`, but it does `-` instead of `+`, as you might already guessed.

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

# Low-level comparison methods

Sometimes, when writing complex SQL queries, you might encounter a situation that available methods can't help you to write a desired query. Here come so-called low-level comparison methods. Let's take a look at available ones:

    openBracket()
    closeBracket()

They open and close brackets respectively.

    rawAnd()
    rawOr()
    
They append `AND` and `OR` respectively. And the reason they were named with `raw` prefix is because, `and` and `or` are reserved names in PHP and cannot be used to name methods.

    compare($column, $operator, $value, $filter = false)
    equals($column, $value, $filter = false)
    notEquals($column, $value, $filter = false)
    like($column, $value, $filter = false)
    greaterThan($column, $value, $filter = false)
    lessThan($column, $value, $filter = false)
    greaterThanOrEquals($column, $value, $filter = false)
    lessThanOrEquals($column, $value, $filter = false)

And finally these methods are similar to previous ones, except that they don't add `WHERE` to expressions.


# Relations

In relation databases, dealing with relation tables is a very common task. For example, if you have a blog, then its posts you'd store in one table and its comments that linked to a blog's post in another.

Of course, you can do this all yourself manually, writing `JOIN` queries or you can use built-in tools, that help you to achieve the same with a one line of code.

As you might already know from the theory, there are 3 common relation types: One-To-One, One-To-Many and Many-To-Many.

So let's take a peek at available methods now.

# One-To-One

    \Krystal\Db\Sql\Db::asOneToOne($column, $alias, $table, $link)


### Parameters

    $column
Column name which is linked to another table

    $alias

Virtual column name to be created for holding results from another table

    $table

Slave (second) table name

    $link

Linking column name from second table

### Example

This is when you you have one master table where you store all common data about an entity linking one of its columns to a slave table.

Consider this:

Suppose there's a table called `books`, that looks so:

     id   |       title          |   year   |   author_id
     1    |    Learn PHP         |   2013   |       1
     2    |    Learn Angular.js  |   2014   |       2
 
 And there's a table for authors, which is `book_authors`:

     id   |    name     | 
     1    |    Daniel   |  
     2    |    Mark     |   
     
To fetch a result-set in one call, you'd do something like this:

    $this->db->select('*')
             ->from('books')
             ->asOneToOne('author_id', 'author', 'book_authors', 'id')
             ->queryAll();


# One-To-Many

    \Krystal\Db\Sql\Db::asOneToMany($table, $pk, $alias)

### Parameters

    $table

Slave (second) table name

    $pk

Primary column name in second table

    $alias

Virtual column name to be appended for holding a result-set.

### Example

You would want to use it, when some entity might contain many attached entities. As a typical example, consider a Blog-application.  Each blog entity might contain unlimited number of comments. And that could look like this:

Master table (let's call it `blog_posts`) for post entities might look like this:

    id  |           title
     1  | Comparing different CMS
     2  | Introduction to Angular.js
 
 And the second (slave) table for comments (let's call it `blog_comments`) might look like this:

    blog_post_id |            comment
         1       |  Nice comparison! Thanks for sharing!
         2       |  Now I'm getting better with Angular! Thanks!


Now let's query these tables using `asOneToMany()` method:

    $this->db->select('*')
             ->from('blog_posts')
             ->asOneToMany('blog_comments', 'blog_post_id', 'comments')

# Many-To-Many

    \Krystal\Db\Sql\DB::asManyToMany($alias, $junction, $column, $table, $pk)

### Parameters

    $alias
Virtual column name to be created

    $junction

Junction table name. This table holds only relations between Primary Keys.

    $column

Column name from junction table to be selected.

    $table

Slave (second) table name.

    $pk
    
Primary column name in slave table.


# Example

Consider a typical scenario : an actor can take part in several movies, and a movie might have several actors.

Let's create 3 tables for this scenario:

Table for movies (let's call it `movies`)

    id  |          title
     1  |   Girls on fire
     2  |   How to get in America

Table for actors (let's call it `actors`)

    id  |    name
     1  |  Jonhy X.Y
     2  |  Mike T.D
     3  |  Karla J.J

And there's a 3-rd table (a.k.a junction) which is responsible for holding relations between movies and actors - `junction`.

    movie_id      |  actor_id
        2         |      1
        2         |      2
        1         |      3
        1         |      1  

Alright, now let's select all actors with their associate movies:

    $this->db->select('*')
             ->from('movies')
             ->asManyToMany('actors', 'junction', 'movie_id', 'actors', 'id')
             ->queryAll();

Similarly, you can fetch all movies with associated actors substituting function arguments respectively.


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

So far, you have learned only how to build queries. Once finish building a query, it won't be executed right away. 

There are there methods, that let you execute your queries, that you built to fetch a result-set:

    \Krystal\Db\Sql\Db::query($column = null, $mode = null)
    \Krystal\Db\Sql\Db::queryAll($column = null, $mode = null)
    \Krystal\Db\Sql\Db::queryScalar($mode = null)

The `query()` fetches a single row, while `queryAll()` fetches all rows. The optional argument `$column` these methods have is about filtering a result-set (i.e the plain array returned array) by a column name. They also allow you to use a different PDO's fetching mode, if you specify a second argument.

The `queryScalar()` is used to fetch a value of a first column.

And there's also one method to execute queries, that never return a result-set. Those queries are typically `INSERT`, `UPDATE` and `DELETE`.

    \Krystal\Db\Sql\Db::execute($rowCount = false)

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

    // Ex.3: Removing all non-activated users
    $this->db->delete()
             ->from('users')
             ->whereEquals('activated', '0')
             ->execute(true); // -> Execute the query returning a number of affected rows
             
The method `execute()` might accept an optional boolean parameter `$rowCount` that can be used to return a number of affected rows instead of returning boolean value.
             
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

And that's it!
Now you can simply call `getPaginator()` on a mapper, since `paginate()` method internally tweaks the paginator. After all you you'd pass paginator's instance to a view from a service later.

# Transactions

There are four methods to handle transaction. In case you use MySQL, remember that transactions aren't supported by MyISAM engine. If you plain to use them, make sure your tables are managed by InnoDB engine.

# beginTransaction()

    \Krystal\Db\Sql\Db\beginTransaction()

Initiates a new transaction. Returns true on success, false on failure

# inTransaction()

    \Krystal\Db\Sql\Db\inTransaction()

Checks if inside a transaction. Returns true if inside, false if not

# commit()

    \Krystal\Db\Sql\Db\commit()

Commits a transaction. Returns true on success, false on failure.

# rollBack()

    \Krystal\Db\Sql\Db\rollBack()

Rolls back a transaction. If no transaction is active, then it would throw `\PDOException`. Returns true on success, false on failure.

# Shortcuts

A shortcut is a method that does some very common thing. Take as example these things:

-- You will be often finding a row by its associated id
-- You will be often removing a row by its associated id
-- You will be often inserting or removing records

And things like that.

To reduce the amount similar queries, Krystal's database component has shortcut methods. 

But in order to start using them, you have to implement two methods in your mapper:

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


# Debugging

Sometimes you might want to determine what queries were executed or how a current query looks like.

# Current query

To view current query, you can simply do `echo $this->db`, since the database object implements `__toString()` method.

# Query logger

Query logger can be accessed like, `$this->db->getQueryLogger()` and it has two available methods.

    getAll()
    getCount()

`getAll()` returns all executed queries, and `getCount()` returns an amount of executed queries.

# Raw queries

It's not a secret that it's very hard to abstract all possible SQL queries, especially for various database engines. Like most popular tools for SQL out there, Krystal abstracts only very common queries. In case you need to execute a non-trivial query, you can do so by invoking `raw()` method on `db` service object.

For example:

    $db = $this->db->raw('SELECT .... UNION SELECT ... WHERE `name` = :name', array(
        // Here come bindings
        ':name' => 'Jonh Doe'
    ));

    $data = $db->queryAll(); // For all matching rows
    
    $data = $db->query(); // For only first matching row

In case you don't expect a result-set back, then you'd better use `$db->execute()`.

# Using raw PDO

Sometimes when using 3-rd party libraries, you might encounter a situation where they require an instance of PDO. To access a raw PDO instance, just call `getPdo()` on database object, like this:

    $pdo = $this->db->getPdo();
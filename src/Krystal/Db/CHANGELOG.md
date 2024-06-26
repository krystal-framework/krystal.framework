CHANGELOG
=========

1.3
---

 * Added `Krystal\Db\Sql\AbstractMapper::valuesExist()` shortcut method
 * Forced to count distinct values, when using smart pagination
 * Improved `update()` method and added standalone `set()` method. This is useful when update needs to be done using JOINs
 * Between generators now accept an instance of `RawSqlFragment` in addition to raw strings when supplying column names
 * The method `QueryBuilder::select()` as of now accepts an instance of `RawSqlFragment`
 * Fixed return value of `Krystal\Db\Sql\AbstractMapper::deleteByColumn()`. Since now it returns boolean value depending on success, rather than a result of execution
 * Since now the `from()` method supports optional table alias, which can be provided as a second argument
 * In database service, added `exists()` and `notExists()` generators
 * In database service, added `expression()` to append raw SQL expression after `select()`
 * The `Krystal\Db\Sql\Db::paginate()` method runs only if first two arguments are numeric. Otherwise just returns an instance of itself
 * In database service added `getVersion()` that returns current database version
 * In database service, added `getPrimaryKey()` that can determine a PK out of a table
 * The `orderBy()` method since now throws `LogicException` if in associative array provided unknown sorting method
 * The `orderBy()` method since now can accept an array with `RawSqlFragment` instances
 * In database service added `whereRandom()`, `andWhereRandom()` and `orWhereRandom()` to abstract building alternative query fragments of `ORDER BY RAND()`
 * Added shared `persistMany()` method
 * Since now `InputDecorator` supports iteration over it
 * Added alias for `getFullColumnName()`
 * Added optional relation short-cuts in JOIN methods
 * Between generators since now support instance of of `RawSqlFragment`
 * Added `func()` generator
 * Support optional user-defined parameters in filter's `invoke()` method
 * Support instances of `RawSqlFragment` in `select()`
 * In `AbstractMapper` added `getRawColumn()` that returns an instance of `RawSqlFragment`
 * Disabled strict mode for MySQL connector by default
 * In `AbstractMapper` added `updateColumns()` updates several columns at once
 * The `delete()` method now accepts optional tables
 * Fixed issue with automatic comma appending when there are selected columns and there's a following function call
 * Minor fix in QueryBuilder - don't quote a string if it's already has quotes
 * Added `replace()` function in `QueryBuilder` and `Db` classes
 * Internal function generator in `QueryBuilder` can now accept arbitrary number of arguments
 * In shared mapper added `updateColumnByPk()` and `updateColumnsByPk()` to simplify updating column values
 * Added `valueExists()` shortcut method in `AbstractMapper`
 * Since now `RawSqlFragment` can accept an instance of `QueryBuilderInterface`
 * Added `whereNotIn()`, `andWhereNotIn()`, `orWhereNotIn()` in database service
 * Support for arbitrary SQL expressions in `whereIn()`, `andWhereIn()`, `orWhereIn()`. To do so, and instance of `\Krystal\Db\Sql\RawSqlFragment` must be passed as an argument
 * Fixed issue with quoting multiple columns
 * In database service added `queryScalar()` method
 * Bug fixes in Filter sub-component
 * Ability to work with unlimited number of junction tables from a single mapper
 * Added raw binding support. To attach a value which must not be binded (e.g a column name) an instance of `\Krystal\Db\Sql\RawBinding` must be supplied as a value argument
 * In `AbstractMapper` added `getFullColumnName()` that returns a full column name (i.e prepending table name)
 * Aggregate function generators can now accept raw SQL fragments
 * Added `in()` method in database service
 * Fixed `whereIn()` and added `andWhereIn()` or `orWhereIn()` methods in database service
 * Added optional argument in `\Krystal\Db\Sql\Db::execute()` called `$rowCount`, that lets to return a row count instead of boolean value
 * In `AbstractMapper` added `getColumnSumWithAverages()` to return column sum with averages
 * In `AbstractMapper` added `isPrimaryKeyValue()` shortcut to check if the value if a primary key
 * In query builder and database service added public `append()` method to append raw fragments when building complex SQL queries
 * In `\Krystal\Db\Sql\AbstractMapper` added `getColumsSum()` shortcut to count the sum of provided columns
 * In `\Krystal\Db\Sql\AbstractMapper` added `getMaxId()` to return the maximal PK's value
 * In `\Krystal\Db\Sql\AbstractMapper` added `persistRow()` to persist a data-set and return it after doing so with PK
 * Fixed issue with commas when appending several SQL functions after SELECT statement
 * Fixed issue with empty wildcarts appending to the query when invoking a filter
 * Fixed `getLastId()` in database service
 * Internal improvement with PDO placeholder bindings
 * In database service, added `paginateRaw()` to paginate result-sets without guessing
 * In shared mapper added `getMasterIdsFromJunction()` and `getSlaveIdsFromJunction()` to find associated ids
 * Fixed issue with queue overflow when execution relation processor
 * When selecting as many-to-many, it's now possible to select custom columns, instead of default `*`. Custom custom also defaults to `*`
 * In shared mapper added `removeFromJunction()`, `insertIntoJunction()` to simplify common operations
 * In shared mapper added `getMasterIdsFromJunction()`, `getSlaveIdsFromJunction()` to simplify grabbing
 * In shared mapper added `syncWithJunction()` to synchronize junction tables
 * In database service, added `insertIntoJunction()` to simplify inserting records into junction tables
 * In `\Krystal\Db\Sql\AbstractMapper` added the following shortcut methods: `addColumn()`, `dropColumn()`, `renameColumn()`, `alterColumn()`, `createIndex()`, `dropIndex()`, `addPrimaryKey()`, `dropPrimaryKey()`
 * Added shortcut constants in `\Krystal\Db\Sql\QueryBuilderInterface` for migration methods
 * Dropped `TableDumper` and moved its methods to database service - `dump()` and `fetchAllTables()`
 * Added `getWithWildcart()` in database service. It can be used to build parameters with wildcarts in LIKE queries
 * Removed `TableBuilder`
 * In database service, added `createIndex()` and `dropIndex()` to create and drop indexes
 * In database service, added `primaryKey()` to create PRIMARY KEYs
 * In database service, added `addConstraint()` and `dropConstraint()`
 * In database service, added `createTable()` method to dynamically create tables
 * In database service, added `renameTable()` method to rename tables
 * In database service, also added methods for migrations: `alterTable()`, `addColumn()`, `dropColumn()`, `renameColumn()`, `alterColumn()`
 * In database service, added missing `notLike()`, `orWhereNotLike()`, `andWhereNotLike()` method
 * In database service, added `insertMany()` and `insertShort()` methods
 * Added `dropTables()` that can drop several tables at once in `Sql\AbstractMapper`
 * Added `executeSqlFromString()` in `Sql\AbstractMapper`
 * Added support for raw SQL fragments for values in WHERE clause
 * Simplified the way of building aliases in SELECT query
 * Added `dropTable()` generator and shortcut
 * Finished partial table prefix implementation
 * Dropped necessary conditional statements in JOIN builders
 * Added optional fillable protection in `Db\Sql\AbstractMapper::persist()` method
 * Added `createPaginator()` in `Krystal\Db\Sql\AbstractMapper`
 * Fixed binding issue with placeholders in `DB::raw()`
 * Fixed binding issue with placeholders in `DB::raw()`
 * Since now shortcut methods are public, not protected as they used to be

1.2
---

 * Added `deleteByPks()` in `Db\Sql\AbstractMapper`
 * Improved internals of `Db\Sql\TableBuilder` . Now it can parse dumps via PDO natively
 * Since now `Db\Sql\TableBuilder` can load data from strings as well. Added new `loadFromString()` method
 * Added ability to re-define default fetching mode in `query()` and `queryAll()` methods
   Since now, the second argument defines a fetching mode
 * Added missing LogicException import in `AbstractMapper`
 * Added asc() to `Db` service

1.1
---

 * Added query logger
 * Added filtering support in methods that implement where() methods
 * Implemented short-cuts for very common table methods in AbstractMapper
 * Added supports for increments and decrements
 * Added new connectors
 * Added COUNT() query guesser. Now pagination is easier like never before! 
   This functionality is wrapped into new method `paginate($page, $itemsPerPage)`

1.0
---

 * First public version
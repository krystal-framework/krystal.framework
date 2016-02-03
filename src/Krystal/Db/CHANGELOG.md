CHANGELOG
=========

1.3
---

 * Added optional fillable protection in `Db\Sql\AbstractMapper::persist()` method
 * Added `createPaginator()` in `Krystal\Db\Sql\AbstractMapper`
 * Fixed binding issue with placeholders in `DB::raw()`
 * Since now shortcut methods are public, not protected as they used to be

1.2
---

 * Added `deleteByPks()` in `Db\Sql\AbstractMapper`
 * Improved internals of `Db\Sql\TableBuilder` . Now it can parse dumps via PDO natively
 * Since now `Db\Sql\TableBuilder` can load data from strings as well. Added new `loadFromString()` method
 * Added ability to re-define default fetching mode in `query()` and `queryAll()` methods
   Since now, the second argument defines a fethcing mode
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
CHANGELOG
=========

1.2
---

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
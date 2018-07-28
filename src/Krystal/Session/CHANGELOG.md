CHANGELOG
=========

1.3
---

 * Added `getOnce()` method
 * Added `getFlashed()` to return flashed session keys (i.e the ones that will be removed after retrieval)
 * Added `hasMany()` to check several keys for existence at once
 * Added `setMany()` to set a collection at once
 * Since now `set()` returns a chain instead of void
 * Since now `remove()` returns a boolean indicating success or failure
 * Added `removeMany()` to remove several keys at once

1.1
---

 * Minor improvements

1.0
---

 * First public version
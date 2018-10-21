CHANGELOG
=========

1.3
---

 * Added `TextUtils::snakeCase()` to convert strings to snake case
 * Added `Math::ceiling()` to round values like in Excel ceiling function
 * Added `Math::numberFormat()` to format numbers without rounding them
 * Added `TextUtils::serial()` that generates serial numbers like XXXXX-XXXXX-XXXXX-XXXXX-XXXXX
 * Added `TextUtils::normalizeColumn()` to convert column names like `first_name` to `First Name`
 * In `Text\SlugGenerator` added `getUniqueSlug()` method to handle uniqueness
 * In `Text\CollectionManager` added `isEmpty()` method that determines whether collection is empty
 * Added `TextUtils::uniqueString()` to generate a very unique string based on MD5
 * In `CollectionManager` added `getKeys()`
 * Added static `Math` class with utility methods
 * In TextUtils added `randomString()`
 * Added `CurrencyConvertor` tool
 * Moved internals of `TextTrimmer` into `TextUtils::trim()`
 * Added `TextUtils::getNeedlePositions()` helper to extra positions of a string to be searched

1.2
---

 * Added `TextUtils::studly()`. It can convert strings to StudlyCase
 * Added `TextUtils::trim()` shortcut
 * Added `TextUtils::sluggify()` shortcut
 * Added `TextUtils::romanize()` shortcut
 * Added `TextUtils::explodeText()` that supports dropping a text into array
 * Added `TextUtils::multiExplode()` that supports dropping a string by several delimiters

1.1
---

 * Improved slug generator

1.0
---

 * First public version
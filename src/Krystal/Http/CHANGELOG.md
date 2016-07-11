CHANGELOG
=========

1.3
---

 * In request service added `isUrlLike()` method
 * In Cookie Bag service added `hasMany()` method
 * In `\Krystal\Http\Response\HttpResponse::setContentType()` dropped second argument
 * Added `authenticate()` method in response service for HTTP authentication
 * Added `respondAsJson()` and `respondAsXml()` methods in response service
 * Added `getHeaderBag()` method in response service to be able to alter headers
 * Set default (one year) lifetime for cookies when setting them
 * Added cURL HTTP crawler
 * Added `getInfo()` in cURL wrapper
 * Fixed issues in Curl wrapper
 * Minor improvements in internals
 * Removed Agent class entirely
 * Since now, it's not possible to set cookie key that has a dot


1.2
---

 * Added `getFile()` to request service

1.1
---

 * Improved Uploader's logic

1.0
---

 * First public version
 
CHANGELOG
=========

1.3
---

 * Made file input handler more robust. Removed deep limitations. Keep indexes on initial array.
 * In `Request` service added `isLocal()` method that determines whether running from local machine or not
 * In `CookieBag` service added `getOnce()` method
 * In `Http\Response\HttpResponse` added refresh()
 * In Request service added `getJsonBody()` and `getXmlBody()` that grab and parse data from raw POST request
 * Fixed issue with HTTPs detection for some server environments
 * In `request` service added `getRawInput()`
 * In cookie bag service, added `setEncrypted()`, `getEncrypted()`
 * In response service added `redirectToHome()` and `redirectToPreviousPage()` shortcut methods
 * In response service added informal methods for HTTP responses
 * In response service added `getStatusCode()` to return assigned HTTP status code
 * In request service added `isUrlLike()` method
 * In Cookie Bag service added `hasMany()` method
 * In `\Krystal\Http\Response\HttpResponse::setContentType()` dropped second argument
 * Added `authenticate()` method in response service for HTTP authentication
 * Added `respondAsJson()`, `respondAsXml()` and `respondAsJs()` methods (quick shortcuts) in response service
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
 
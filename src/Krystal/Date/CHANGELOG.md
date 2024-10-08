CHANGELOG
==========

1.3
---

 * In `TimeHelper` added `getCopyright()` to get a range of years for copyrights
 * In `TimeHelper` added `isTimestamp()` to test whether given string is UNIX-timestamp
 * In `TimeHelper` added `formatLocalized()` to format dates with localization support
 * In `TimeHelper` added `guessSeason()` to determine current season
 * In `TimeHelper` added `formatValid()` methods that checks whether date/time format is valid
 * In `TimeHelper` added `age()` that counts user's age from a birthday
 * In `TimeHelper` added `getDays()` that month days
 * In `TimeHelper` added `getNow()` that returns current date (and time)
 * In `TimeHelper` added `isExpired()` that boolean indicating if the second date is expired regarding first one
 * In `TimeHelper` added `getTakenTime()` that returns the time difference between two timestamp in `Hours:Minutes:Seconds` format
 * In `TimeHelper` added `getMonthDaysCount()` that returns day count that belongs to year & month
 * In `TimeHelper` added `getMonths()`, `getNextMonths()` and `getPreviousMonths()`
 * In `TimeHelper` added helper methods to work with quarters: `getQuarters()`, `getQuarter()` getMonthsByQuarter() and `getAllMonthsByQuarter()`
 * In `TimeHelper` added `createYears()` and `createYearsUpToCurrent()`

1.2
----

 * Added Zodiacal service
 * Renamed `Helper` to `TimeHelper`

1.0
----

 * First public version
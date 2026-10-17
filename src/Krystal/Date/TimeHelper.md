Time Helper
=====

Utility class for working with time and date, providing constants, date calculations, formatting, and helpers for months, quarters, and years.

## Constants

Provides predefined time-related constants in seconds for convenience, including seconds, minutes, days, weeks, months, and years.

    <?php
    
    use Krystal\Date\TimeHelper;
    
    echo TimeHelper::SECOND;
    echo TimeHelper::MINUTE;
    echo TimeHelper::DAY;
    echo TimeHelper::WEEK;
    echo TimeHelper::MONTH;
    echo TimeHelper::YEAR;

## Getting relative time

Returns a relative-time description for a timestamp. The result is split into a `pattern` and a `value` so the caller can translate and pluralize it.

- `pattern` — a ready-to-display string (`'just now'`, an absolute date) **or** a template containing one `%d` placeholder.
- `value` — `null` when `pattern` is ready to display, otherwise the integer to substitute into `%d`.

Accepts either a UNIX timestamp or any string understood by `strtotime()`. If parsing fails, an empty `pattern` and `null` `value` are returned.

    use Krystal\Date\TimeHelper;

    $result = TimeHelper::relativeTimePattern('2025-03-01 10:00:00');

    if ($result['value'] === null) {
        echo $result['pattern']; // "just now", or e.g. "Mar 4, 2025 14:30"
    } else {
        echo sprintf($result['pattern'], $result['value']); // e.g. "5 minutes ago"
    }

Thresholds are coarse and fixed — they are **not** calendar-aware:

| Difference from now | `pattern` | `value` |
|---|---|---|
| < 1 minute | `'just now'` | `null` |
| < 1 hour | `'%d minutes ago'` | minutes |
| < 1 day | `'%d hours ago'` | hours |
| < 30 days | `'%d days ago'` | days |
| otherwise, or **future** | absolute date, e.g. `'Mar 4, 2025 14:30'` | `null` |

A **future** timestamp does not produce a relative description — it falls through to the absolute date branch. The 30-day month cutoff and the 365-day year constant are approximations; for calendar-accurate output, use `DateTime::diff()` directly.

The optional second argument overrides the current time. Leave it `null` in normal code; pass it in tests for a deterministic result:

    $result = TimeHelper::relativeTimePattern(1709287200, 1709287260);
    // ['pattern' => '%d minutes ago', 'value' => 1]

The caller is responsible for pluralization and localization:

    $text = $result['value'] === 1
        ? 'a minute ago'
        : sprintf('%d minutes ago', $result['value']);

    // Translation (gettext-style)
    $text = sprintf(_($result['pattern']), $result['value']);

## Getting copyright year

Returns a copyright year or range from start year to current year.

    echo  TimeHelper::getCopyright(2020); // 2020 - 2025 (if current year is 2025)

## Checking timestamp validity

Check if timestamp is valid

    var_dump(TimeHelper::isTimestamp(1609459200)); // true
    var_dump(TimeHelper::isTimestamp("not a timestamp")); // false

## Formatting localized date

Formats a date in a localized pattern using ICU formatting.

    echo TimeHelper::formatLocalized("2025-10-04", "en_US", "MMMM dd, yyyy");
    // October 04, 2025

## Guessing current season

Guesses the current season based on the Northern Hemisphere calendar.

    echo TimeHelper::guessSeason(); // Spring / Summer / Fall / Winter

## Checking datetime validity

Checks whether a datetime string is valid.

    var_dump(TimeHelper::formatValid("2025-10-04")); // true
    var_dump(TimeHelper::formatValid("invalid-date")); // false

## Getting age

Calculates age from a given birth date.

    echo TimeHelper::age("1990-05-15"); // 35 (if current year is 2025)

## Getting all days of the month

Returns all days of the month as an array.

    print_r(TimeHelper::getDays());

Output

    Array
    (
        [1] => 1
        [2] => 2
        ...
        [31] => 31
    )

## Getting current date/time

Returns the current date, optionally with time.

    echo TimeHelper::getNow(); // 2025-10-04 12:34:56
    echo TimeHelper::getNow(false); // 2025-10-04


## Checking if date is expired

Checks whether the end date has passed compared to a start date.

    var_dump(TimeHelper::isExpired("2025-10-01", "2025-10-04")); // false
    var_dump(TimeHelper::isExpired("2025-10-05", "2025-10-04")); // true

## Calculating time difference

Calculates time difference between two timestamps.

    $start = strtotime("2025-10-04 08:00:00");
    $end = strtotime("2025-10-04 12:30:45");
    
    echo TimeHelper::getTakenTime($start, $end); // 04:30:45

## Getting days count in month

Returns number of days in a specific month and year.

    echo TimeHelper::getMonthDaysCount(2, 2024); // 29 (leap year)

## Getting previous months

Returns months preceding the target month.

    print_r(TimeHelper::getPreviousMonths("05"));
    // ['01', '02', '03', '04', '05']


## Getting next months

Returns months following the target month.

    print_r(TimeHelper::getNextMonths("05"));
    // ['05', '06', '07', '08', '09', '10', '11', '12']

## Getting all months

Returns all months with names.

    print_r(TimeHelper::getMonths());

Output

    Array
    (
        ['01'] => January
        ['02'] => February
        ...
        ['12'] => December
    )

## Getting all quarters

Returns all quarters in a year.

    print_r(TimeHelper::getQuarters()); // [1, 2, 3, 4]

## Getting all months up to quarter

Returns months from Q1 up to a given quarter.

    print_r(TimeHelper::getAllMonthsByQuarter(2));
    // ['01', '02', '03', '04', '05', '06']


## Getting months by quarter

Returns months of a specific quarter.

    print_r(TimeHelper::getMonthsByQuarter(3)); // ['07','08','09']


## Getting quarter of a month

    echo TimeHelper::getQuarter(5); // 2
    echo TimeHelper::getQuarter(); // current quarter

## Creating year range

Generates an array of years from start to end.

    print_r(TimeHelper::createYears(2020, 2025));

Output

    Array
    (
        [2020] => 2020
        [2021] => 2021
        ...
        [2025] => 2025
    )

## Creating years up to current

Generates an array of years from start up to the current year.

    print_r(TimeHelper::createYearsUpToCurrent(2018));

Output

    Array
    (
        [2018] => 2018
        [2019] => 2019
        ...
        [2025] => 2025
    )

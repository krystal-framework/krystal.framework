<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Date;

use LogicException;
use DateTime;
use IntlDateFormatter;
use Exception;

abstract class TimeHelper
{
    const MINUTE = 60;
    const SECOND = 1;
    const DAY = 86400;
    const WEEK = 604800;
    const MONTH = 2592000;
    const YEAR = 31536000;

    /**
     * Returns a copyright year or year range from the given start year up to the current year.
     *
     * If the start year is the same as the current year, it returns a single year.
     * Otherwise, it returns a range in the format "start - current".
     *
     * @param int|string $start The starting year for the copyright
     * @return string The copyright year or year range
     */
    public static function getCopyright($start)
    {
        $currentYear = date('Y');

        if ($currentYear == $start) {
            return (string) $start;
        } else {
            return sprintf('%s - %s', $start, $currentYear);
        }
    }

    /**
     * Checks whether a given value represents a valid UNIX timestamp.
     *
     * A valid timestamp is numeric, non-negative, and can be used to create a DateTime object.
     *
     * @param mixed $value The value to check
     * @return bool True if the value is a valid UNIX timestamp, false otherwise
     */
    public static function isTimestamp($value)
    {
        if (is_numeric($value) && ((int) $value >= 0)) {
            try {
                new DateTime('@' . $value);
                return true;
            } catch(Exception $e) {}
        }

        return false;
    }

    /**
     * Formats a date in a localized format using IntlDateFormatter.
     *
     * If the target date is null, the current date and time will be used.
     *
     * @param string|null $target Any datetime string recognized by PHP's DateTime, or null for now
     * @param string $locale The locale code, e.g., 'en_US', 'fr_FR'
     * @param string $pattern The ICU date/time pattern (see https://unicode-org.github.io/icu/userguide/format_parse/datetime/)
     * @return string The formatted localized date
     *
     * @throws \Exception If the DateTime or IntlDateFormatter fails to initialize
     */
    public static function formatLocalized($target, $locale, $pattern)
    {
        $dt = $target ? new DateTime($target) : new DateTime();

        $intlFormatter = new IntlDateFormatter($locale, IntlDateFormatter::FULL, IntlDateFormatter::FULL);
        $intlFormatter->setPattern($pattern);

        return $intlFormatter->format($dt);
    }

    /**
     * Guesses the current season based on the current date.
     *
     * The seasons are determined by approximate Northern Hemisphere dates:
     * - Spring: March 20 – June 20
     * - Summer: June 21 – September 22
     * - Fall: September 23 – December 20
     * - Winter: December 21 – March 19
     *
     * @return string The current season: 'Spring', 'Summer', 'Fall', or 'Winter'
     */
    public static function guessSeason()
    {
        $md = (int) date('md');

        if ($md >= 320 && $md < 621) {
            return 'Spring';
        }

        if ($md >= 621 && $md < 923) {
            return 'Summer';
        }

        if ($md >= 923 && $md < 1221) {
            return 'Fall';
        }

        return 'Winter';
    }

    /**
     * Checks whether a given datetime string is valid.
     *
     * A datetime string is considered valid if it can be used to create a DateTime object.
     *
     * @param string $datetime The datetime string to validate
     * @return bool True if the datetime is valid, false otherwise
     */
    public static function formatValid($datetime)
    {
        try {
            new DateTime($datetime);
            return true;
        } catch(Exception $e){
            return false;
        }
    }

    /**
     * Calculates age based on a given birth date.
     *
     * The birth date can be any format accepted by PHP's DateTime class.
     *
     * @param string $birth The birth date string
     * @return int The calculated age in years
     *
     * @throws \Exception If the birth date string is invalid
     */
    public static function age($birth)
    {
        $date = new DateTime($birth);
        $now = new DateTime();

        $interval = $now->diff($date);

        return $interval->y;
    }

    /**
     * Returns a collection of all possible days in a month.
     *
     * The returned array maps day numbers to themselves, from 1 to 31.
     *
     * @return array<int, int> Array of day numbers (1 => 1, 2 => 2, ..., 31 => 31)
     */
    public static function getDays()
    {
        $range = range(1, 31);
        return array_combine($range, $range);
    }

    /**
     * Returns the current date, optionally including the current time.
     *
     * @param bool $time Whether to include the current time. Defaults to true.
     * @return string The current date in 'Y-m-d' format, or 'Y-m-d H:i:s' if $time is true
     */
    public static function getNow($time = true)
    {
        if ($time === true) {
            $format = 'Y-m-d H:i:s';
        } else {
            $format = 'Y-m-d';
        }

        return date($format);
    }

    /**
     * Checks whether a given end date has already passed compared to a start date.
     *
     * @param string $startDate The starting date (any format recognized by strtotime)
     * @param string $endDate The ending date (any format recognized by strtotime)
     * @return bool True if the end date is earlier than or equal to the start date, false otherwise
     */
    public static function isExpired($startDate, $endDate)
    {
        return strtotime($endDate) <= strtotime($startDate);
    }

    /**
     * Calculates the time difference between two timestamps and returns it in H:i:s format.
     *
     * @param int $startTimestamp The starting timestamp (UNIX timestamp)
     * @param int $endTimestamp The ending timestamp (UNIX timestamp)
     * @return string Time difference formatted as 'HH:MM:SS'
     */
    public static function getTakenTime($startTimestamp, $endTimestamp)
    {
        $datetime1 = (new DateTime())->setTimestamp($startTimestamp);
        $datetime2 = (new DateTime())->setTimestamp($endTimestamp);
        
        $interval = $datetime1->diff($datetime2);
        return $interval->format('%H:%I:%S');
    }

    /**
     * Returns the number of days in a given month and year.
     *
     * @param int $month The month number (1-12)
     * @param int $year The year (e.g., 2025)
     * @return int Number of days in the specified month
     */
    public static function getMonthDaysCount($month, $year)
    {
        return cal_days_in_month(CAL_GREGORIAN, $month, $year);
    }

    /**
     * Returns a sequence of months up to a target month.
     *
     * This method iterates over the given months array and collects months
     * until the target month is reached. Optionally, the target month itself
     * can be included in the result.
     *
     * @param array $months Array of month identifiers (e.g., '01', '02', ...)
     * @param string $target Target month to stop at
     * @param bool $withCurrent Whether to include the target month in the result
     * @throws \LogicException If the target month is not in the given months array
     * @return array Array of month identifiers up to (and optionally including) the target
     */
    private static function getMonthSequence(array $months, $target, $withCurrent)
    {
        if (!in_array($target, $months)) {
            throw new LogicException(
                sprintf('Target month "%s" is out of range. The range must be from 01 up to 12', $target)
            );
        }

        $collection = array();

        foreach ($months as $month) {
            if ($month == $target) {
                if ($withCurrent === true) {
                    $collection[] = $month;
                }
                break;
            } else {
                $collection[] = $month;
            }
        }

        return $collection;
    }

    /**
     * Returns a collection of months preceding the target month.
     *
     * Uses all months from January to December and returns the sequence
     * of months up to the target month. Optionally, the target month
     * itself can be included.
     *
     * @param string $target Target month in '01'..'12' format
     * @param bool $withCurrent Whether to include the target month in the result
     * @return array Array of month identifiers leading up to the target
     * @throws \LogicException If the target month is out of range
     */
    public static function getPreviousMonths($target, $withCurrent = true)
    {
        return self::getMonthSequence(array_keys(self::getMonths()), $target, $withCurrent);
    }

    /**
     * Returns a collection of months following the target month.
     *
     * Uses all months from January to December and returns the sequence
     * of months after the target month. Optionally, the target month
     * itself can be included.
     *
     * @param string $target Target month in '01'..'12' format
     * @param bool $withCurrent Whether to include the target month in the result
     * @return array Array of month identifiers following the target
     * @throws \LogicException If the target month is out of range
     */
    public static function getNextMonths($target, $withCurrent = true)
    {
        $months = array_keys(self::getMonths());
        $months = array_reverse($months);

        $result = self::getMonthSequence($months, $target, $withCurrent);
        return array_reverse($result);
    }

    /**
     * Returns a collection of month numbers with their corresponding names.
     *
     * The keys are month numbers with leading zeros ('01'..'12'),
     * and the values are the English month names.
     *
     * @return array<string, string> Associative array of month numbers => month names
     */
    public static function getMonths()
    {
        return array(
            '01' => 'January',
            '02' => 'February',
            '03' => 'March',
            '04' => 'April',
            '05' => 'May',
            '06' => 'June',
            '07' => 'July',
            '08' => 'August',
            '09' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December'
        );
    }

    /**
     * Returns the available quarters in a year.
     *
     * Quarters are represented as integers from 1 to 4.
     *
     * @return int[] Array of quarters [1, 2, 3, 4]
     */
    public static function getQuarters()
    {
        return range(1, 4);
    }

    /**
     * Returns a collection of months up to the given quarter.
     *
     * For example, if $quarter = 2, it returns all months from Q1 and Q2:
     * ['01', '02', '03', '04', '05', '06'].
     *
     * @param int $quarter Quarter number (1-4)
     * @throws \LogicException If an invalid quarter is supplied
     * @return string[] Array of month numbers with leading zeros
     */
    public static function getAllMonthsByQuarter($quarter)
    {
        if ($quarter < 1 || $quarter > 4) {
            throw new LogicException("Invalid quarter supplied - $quarter");
        }

        $result = [];

        for ($i = 1; $i <= $quarter; $i++) {
            $result = array_merge($result, self::getMonthsByQuarter($i));
        }

        return $result;
    }

    /**
     * Returns a collection of months for a given quarter.
     *
     * Quarters are defined as:
     * 1 => ['01', '02', '03']
     * 2 => ['04', '05', '06']
     * 3 => ['07', '08', '09']
     * 4 => ['10', '11', '12']
     *
     * @param int $quarter Quarter number (1-4)
     * @throws \LogicException If an invalid quarter is supplied
     * @return string[] Array of month numbers with leading zeros
     */
    public static function getMonthsByQuarter($quarter)
    {
        switch ($quarter) {
            case 1:
                return array('01', '02', '03');
            case 2:
                return array('04', '05', '06');
            case 3:
                return array('07', '08', '09');
            case 4:
                return array('10', '11', '12');
            default:
                throw new LogicException(sprintf('Invalid quarter supplied - %s', $quarter));
        }
    }

    /**
     * Returns the quarter number for a given month.
     *
     * If no month is provided, the current month is used.
     * Quarters are defined as:
     * 1 => Jan-Mar
     * 2 => Apr-Jun
     * 3 => Jul-Sep
     * 4 => Oct-Dec
     *
     * @param int|null $month Month number (1-12), optional
     * @return int Quarter number (1-4)
     */
    public static function getQuarter($month = null)
    {
        $month = $month ?? (int) date('n');
        return (int) ceil($month / 3);
    }

    /**
     * Generates a sequential array of years from start to end (inclusive).
     *
     * The returned array has both keys and values set as the year numbers.
     *
     * @param int $start Starting year
     * @param int $end Ending year
     * @return array<int, int> Array of years
     */
    public static function createYears($start, $end)
    {
        $result = array();

        for ($i = $start; $i < $end + 1; $i++) {
            $result[$i] = $i;
        }

        return $result;
    }

    /**
     * Generates a sequential array of years from the given start year up to the current year (inclusive).
     *
     * @param int $start Starting year
     * @return array<int, int> Array of years
     */
    public static function createYearsUpToCurrent($start)
    {
        return self::createYears($start, (int) date('Y'));
    }
}

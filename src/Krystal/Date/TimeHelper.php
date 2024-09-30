<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Date;

use LogicException;
use DateTime;
use IntlDateFormatter;
use Exception;

/**
 * Time related helper methods 
 */
abstract class TimeHelper
{
    const MINUTE = 60;
    const SECOND = 1;
    const DAY = 86400;
    const WEEK = 604800;
    const MONTH = 2592000;
    const YEAR = 31536000;

    /**
     * Returns a year range for copyrights
     * 
     * @param string $start Starting year
     * @return string
     */
    public static function getCopyright($start)
    {
        $currentYear = date('Y');

        if ($currentYear == $start) {
            return $start;
        } else {
            return sprintf('%s - %s', $start, $currentYear);
        }
    }

    /**
     * Checks whether string is a UNIX timestamp
     * 
     * @param string $string
     * @return boolean
     */
    public static function isTimestamp($value)
    {
        try {
            new DateTime('@' . $value);
            return true;
        } catch(Exception $e) {
            return false;
        }
    }

    /**
     * Formats date in localized format
     * 
     * @param string $target Any datetime that can be handled by native \DateTime class. If NULL - then current date and time used
     * @param string $locale
     * @param string $pattern The documentation can be found at https://unicode-org.github.io/icu/userguide/format_parse/datetime/
     * @return string
     */
    public static function formatLocalized($target, $locale, $pattern)
    {
        $dt = new DateTime($target);
        $intlFormatter = new IntlDateFormatter($locale, IntlDateFormatter::FULL, IntlDateFormatter::FULL);
        $intlFormatter->setPattern($pattern);

        return $intlFormatter->format($dt);
    }

    /**
     * Guess current season
     * 
     * @return string
     */
    public static function guessSeason()
    {
        $date = new DateTime();

        // Get the season dates
        $winter = new DateTime('December 21');
        $spring = new DateTime('March 20');
        $summer = new DateTime('June 20');
        $fall = new DateTime('September 22');

        switch (true) {
            case $date >= $spring && $date < $summer:
                return 'Spring';
            case $date >= $summer && $date < $fall:
                return 'Summer';
            case $date >= $fall && $date < $winter:
                return 'Fall';
            default:
                return 'Winter';
        }
    }

    /**
     * Checks whether datetime format is valid
     * 
     * @param string $datetime
     * @return boolean
     */
    public static function formatValid($datetime)
    {
        try {
            new Datetime($datetime);
            return true;
        } catch(Exception $e){
            return false;
        }
    }

    /**
     * Calculates an from a date of birth
     * 
     * @param string $birth (Any date format supported by DateTime)
     * @return int
     */
    public static function age($birth)
    {
        $date = new DateTime($birth);
        $now = new DateTime();

        $interval = $now->diff($date);

        return $interval->y;
    }

    /**
     * Returns month days
     * 
     * @return array
     */
    public static function getDays()
    {
        $range = range(1, 31);
        return array_combine($range, $range);
    }

    /**
     * Returns current date (and time)
     * 
     * @param boolean $time Whether to append current time
     * @return string
     */
    public static function getNow($time = true)
    {
        // Dynamic format depending time requirement
        if ($time === true) {
            $format = 'Y-m-d H:i:s';
        } else {
            $format = 'Y-m-d';
        }

        return date($format);
    }

    /**
     * Checks whether a date expired regarding first one
     * 
     * @param string $startDate Starting date with time
     * @param string $endDate Ending date with time
     * @return boolean
     */
    public static function isExpired($startDate, $endDate)
    {
        return strtotime($startDate) - strtotime($endDate) <= 0;
    }

    /**
     * Returns time difference between two timestamps (in Hours:Minutes:Seconds format)
     * 
     * @param string $startTimestamp
     * @param string $endTimestamp
     * @return string
     */
    public static function getTakenTime($startTimestamp, $endTimestamp)
    {
        // Mostly used format
        $format = 'Y-m-d H:i:s';

        $datetime1 = new DateTime(date($format, $startTimestamp));
        $datetime2 = new DateTime(date($format, $endTimestamp));

        $interval = $datetime1->diff($datetime2);
        return $interval->format('%H:%I:%S');    
    }

    /**
     * Returns amount of days associated with year & month
     * 
     * @param string $month
     * @param string $year
     * @return integer
     */
    public static function getMonthDaysCount($month, $year)
    {
        return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
    }

    /**
     * Returns months sequence
     * 
     * @param array $months
     * @param string $target Target months
     * @param boolean $withCurrent Whether to include target months in resultset
     * @throws \LogicException if $target is out of range
     * @return array
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
     * Returns a collection of previous months
     * 
     * @param string $target
     * @param string $withCurrent Whether to include $target in resultset
     * @return array
     */
    public static function getPreviousMonths($target, $withCurrent = true)
    {
        return self::getMonthSequence(array_keys(self::getMonths()), $target, $withCurrent);
    }

    /**
     * Returns a collection of next months
     * 
     * @param string $target
     * @param string $withCurrent Whether to include $target in resultset
     * @return array
     */
    public static function getNextMonths($target, $withCurrent = true)
    {
        $months = array_keys(self::getMonths());
        $months = array_reverse($months);

        $result = self::getMonthSequence($months, $target, $withCurrent);
        return array_reverse($result);
    }

    /**
     * Returns a collection of month numbers with leading zeros
     * 
     * @return array
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
     * Return quarters
     * 
     * @return array
     */
    public static function getQuarters()
    {
        return range(1, 4);
    }

    /**
     * Returns a collection of months by associated quarter
     * 
     * @param integer $quarter
     * @throws \LogicException If invalid quarter supplied
     * @return array
     */
    public static function getAllMonthsByQuarter($quarter)
    {
        switch ($quarter) {
            case 1:
                return self::getMonthsByQuarter(1);
            case 2:
                return array_merge(self::getMonthsByQuarter(1), self::getMonthsByQuarter(2));
            case 3:
                return array_merge(self::getMonthsByQuarter(1), self::getMonthsByQuarter(2), self::getMonthsByQuarter(3));
            case 4:
                return array_merge(self::getMonthsByQuarter(1), self::getMonthsByQuarter(2), self::getMonthsByQuarter(3), self::getMonthsByQuarter(4));
            default:
                throw new LogicException(sprintf('Invalid quarter supplied - %s', $quarter));
        }
    }

    /**
     * Returns a collection of months by associated quarter
     * 
     * @param integer $quarter
     * @throws \LogicException If invalid quarter supplied
     * @return array
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
     * Returns current quarter
     * 
     * @param integer $month Month number without leading zeros
     * @return integer
     */
    public static function getQuarter($month = null)
    {
        if ($month === null) {
            $month = date('n', abs(time()));
        }

        if (in_array($month, range(1, 3))) {
            return 1;
        }

        if (in_array($month, range(4, 6))) {
            return 2;
        }

        if (in_array($month, range(7, 9))) {
            return 3;
        }

        if (in_array($month, range(10, 12))) {
            return 4;
        }
    }

    /**
     * Create years
     * 
     * @param integer $start Starting year
     * @param integer $end Ending year
     * @return array
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
     * Create years up to current one
     * 
     * @param integer $start Starting year
     * @param integer $end Ending year
     * @return array
     */
    public static function createYearsUpToCurrent($start)
    {
        return self::createYears($start, date('Y'));
    }
}

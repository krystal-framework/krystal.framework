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

use DateTime;
use InvalidArgumentException;
use RuntimeException;

/**
 * Determines zodiac signs based on date ranges
 */
final class Zodiacal implements ZodiacalInterface
{
    /**
     * DateTime instance
     * 
     * @var \DateTime
     */
    private $date;

    /**
     * Zodiac sign date ranges
     * 
     * @var array
     */
    private $signRanges = array(
        'Aries'       => array('03-21', '04-19'),
        'Taurus'      => array('04-20', '05-20'),
        'Gemini'      => array('05-21', '06-20'),
        'Cancer'      => array('06-21', '07-22'),
        'Leo'         => array('07-23', '08-22'),
        'Virgo'       => array('08-23', '09-22'),
        'Libra'       => array('09-23', '10-22'),
        'Scorpio'     => array('10-23', '11-21'),
        'Sagittarius' => array('11-22', '12-21'),
        'Capricorn'   => array('12-22', '01-19'),
        'Aquarius'    => array('01-20', '02-18'),
        'Pisces'      => array('02-19', '03-20')
    );

    /**
     * State initialization
     * 
     * @param int $month 1-12
     * @param int $day
     * @throws \InvalidArgumentException
     */
    public function __construct($month, $day)
    {
        if (!is_numeric($month) || $month < 1 || $month > 12) {
            throw new InvalidArgumentException('Month must be between 1-12');
        }

        if (!is_numeric($day)) {
            throw new InvalidArgumentException('Day must be numeric');
        }

        $year = date('Y'); // Current year for leap year calculation
        $this->date = DateTime::createFromFormat('!Y-n-j', "$year-$month-$day");

        if (!$this->date) {
            throw new InvalidArgumentException('Invalid date');
        }
    }

    /**
     * Create from DateTime object
     * 
     * @param DateTime $dateTime
     * @return \Krystal\Date\Zodiacal
     */
    public static function fromDateTime(DateTime $dateTime)
    {
        $instance = new self(1, 1);
        $instance->date = $dateTime;

        return $instance;
    }

    /**
     * Check if date matches specific sign
     * 
     * @param string $sign
     * @return bool
     */
    public function is($sign)
    {
        return $this->getSign() === ucfirst(strtolower($sign));
    }

    /**
     * Get zodiac sign for current date
     * 
     * @return string
     */
    public function getSign()
    {
        $dateStr = $this->date->format('m-d');
        $year = $this->date->format('Y');

        foreach ($this->signRanges as $sign => $range) {
            $start = DateTime::createFromFormat('!Y-m-d', "$year-{$range[0]}");
            $end = DateTime::createFromFormat('!Y-m-d', "$year-{$range[1]}");

            // Handle Capricorn (crosses year boundary)
            if ($sign === 'Capricorn' && $end < $start) {
                $end->modify('+1 year');
            }

            if ($this->date >= $start && $this->date <= $end) {
                return $sign;
            }
        }

        throw new RuntimeException('Failed to determine zodiac sign');
    }

    /**
     * Get all available zodiac signs
     * 
     * @return array
     */
    public function getAvailableSigns()
    {
        return array_keys($this->signRanges);
    }
}

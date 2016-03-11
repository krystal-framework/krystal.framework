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

use UnexpectedValueException;
use OutOfRangeException;

final class Zodiacal implements ZodiacalInterface
{
    /**
     * Current month
     * 
     * @var string
     */
    private $month;

    /**
     * Current day
     * 
     * @var integer
     */
    private $day;

    const MONTH_JANUARY = 'January';
    const MONTH_FEBRUARY = 'February';
    const MONTH_MARCH = 'March';
    const MONTH_APRIL = 'April';
    const MONTH_MAY = 'May';
    const MONTH_JUNE = 'June';
    const MONTH_JULY = 'July';
    const MONTH_AUGUST = 'August';
    const MONTH_SEPTEMBER = 'September';
    const MONTH_OCTOBER = 'October';
    const MONTH_NOVEMBER = 'November';
    const MONTH_DECEMBER = 'December';

    /**
     * State initialization
     * 
     * @param string $month
     * @param integer $day
     * @throws \UnexpectedValueException If unknown month supplied
     * @throws \OutOfRangeException If a day is out of range
     * @return void
     */
    public function __construct($month, $day)
    {
        $month = $this->normalize($month);
        $day = (int) $day;

        if (!$this->isValidMonth($month)) {
            throw new UnexpectedValueException(sprintf('Unknown month supplied "%s"', $month));
        }

        if (!$this->dayInRange($day)) {
            throw new OutOfRangeException(sprintf('A day must be in rage of 1-31. Invalid number supplied "%s"'));
        }

        $this->month = $month;
        $this->day = $day;
    }

    /**
     * Manually checks whether $sign equals to calculated one
     * 
     * @param string $sign
     * @return boolean
     */
    public function is($sign)
    {
        return $this->getSign() === $this->normalize($sign);
    }

    /**
     * Gets a zodiacal sign based on a month and a day
     * 
     * @return string|boolean The name, false on failure
     */
    public function getSign()
    {
        foreach ($this->getMap() as $name => $method) {
            // Call associated method dynamically
            if (call_user_func(array($this, $method))) {
                return $name;
            }
        }

        // On failure
        return false;
    }

    /**
     * Checks whether day is in range
     * 
     * @param integer $day
     * @return boolean
     */
    private function dayInRange($day)
    {
        return (1 <= $day && $day <= 31);
    }

    /**
     * Checks whether month is supported
     * 
     * @param string $month
     * @return boolean
     */
    private function isValidMonth($month)
    {
        $list = array(
            self::MONTH_JANUARY,
            self::MONTH_FEBRUARY,
            self::MONTH_MARCH,
            self::MONTH_APRIL,
            self::MONTH_MAY,
            self::MONTH_JUNE,
            self::MONTH_JULY,
            self::MONTH_AUGUST,
            self::MONTH_SEPTEMBER,
            self::MONTH_OCTOBER,
            self::MONTH_NOVEMBER,
            self::MONTH_DECEMBER
        );

        return in_array($month, $list);
    }

    /**
     * Returns a map of zodiacal sign names with their associated method names
     * 
     * @return array
     */
    private function getMap()
    {
        // Zodiacal sing name => the method that confirms if it matches
        return array(
            'Aries' => 'isAries',
            'Taurus' => 'isTaurus',
            'Gemini' => 'isGemini',
            'Cancer' => 'isCancer',
            'Leo' => 'isLeo',
            'Virgo' => 'isVirgo',
            'Scorpio' => 'isScorpio',
            'Libra' => 'isLibra',
            'Sagittarius' => 'isSagittarius',
            'Capricorn' => 'isCapricorn',
            'Aquarius' => 'isAquarius',
            'Pisces' => 'isPisces',
        );
    }

    /**
     * Normalizes a month
     * 
     * @param string $month
     * @return string
     */
    private function normalize($month)
    {
        return ucfirst(strtolower($month));
    }

    /**
     * Checks whether the sign is Aries
     * 
     * @return boolean
     */
    public function isAries()
    {
        return ($this->month === self::MONTH_MARCH) && ($this->day >= 21) && ($this->day <= 31) || ($this->month === self::MONTH_APRIL) && ($this->day <= 20);
    }

    /**
     * Checks whether the sign is Taurus
     * 
     * @return boolean
     */
    public function isTaurus()
    {
        return ($this->month === self::MONTH_APRIL) && ($this->day >= 21) && ($this->day <= 30) || ($this->month === self::MONTH_MAY) && ($this->day <= 21);
    }

    /**
     * Checks whether the sign is Gemini
     * 
     * @return boolean
     */
    public function isGemini()
    {
        return ($this->month === self::MONTH_MAY) && ($this->day >= 22) && ($this->day <= 31) || ($this->month === self::MONTH_JULY) && ($this->day <= 21);
    }

    /**
     * Checks whether the sign is Cancer
     * 
     * @return boolean
     */
    public function isCancer()
    {
        return ($this->month === self::MONTH_JUNE) && ($this->day >= 22) && ($this->day <= 30) || ($this->month === self::MONTH_JULY) && ($this->day <= 22);
    }

    /**
     * Checks whether the sign is Leo
     * 
     * @return boolean
     */
    public function isLeo()
    {
        return ($this->month === self::MONTH_JULY) && ($this->day >= 23) && ($this->day <= 30) || ($this->month === self::MONTH_MAY) && ($this->day <= 22);
    }

    /**
     * Checks whether the sign is Virgo
     * 
     * @return boolean
     */
    public function isVirgo()
    {
        return ($this->month === self::MONTH_AUGUST) && ($this->day >= 23) && ($this->day <= 30) || ($this->month === self::MONTH_SEPTEMBER) && ($this->day <= 23);
    }

    /**
     * Checks whether the sign is Scorpio
     * 
     * @return boolean
     */
    public function isScorpio()
    {
        return ($this->month === self::MONTH_OCTOBER) && ($this->day >= 24) && ($this->day <= 30) || ($this->month === self::MONTH_NOVEMBER) && ($this->day <= 22);
    }

    /**
     * Checks whether the sign is Libra
     * 
     * @return boolean
     */
    public function isLibra()
    {
        return ($this->month === self::MONTH_SEPTEMBER) && ($this->day >= 24) && ($this->day <= 30) || ($this->month === self::MONTH_OCTOBER) && ($this->day <= 23);
    }

    /**
     * Checks whether the sign is Sagittarius
     * 
     * @return boolean
     */
    public function isSagittarius()
    {
        return ($this->month === self::MONTH_NOVEMBER) && ($this->day >= 23) && ($this->day <= 30) || ($this->month === self::MONTH_DECEMBER) && ($this->day <= 21);
    }

    /**
     * Checks whether the sign is Capricorn
     * 
     * @return boolean
     */
    public function isCapricorn()
    {
        return ($this->month === self::MONTH_DECEMBER) && ($this->day >= 22) && ($this->day <= 30) || ($this->month === self::MONTH_JANUARY) && ($this->day <= 20);
    }

    /**
     * Checks whether the sign is Aquarius
     * 
     * @return boolean
     */
    public function isAquarius()
    {
        return ($this->month === self::MONTH_JANUARY) && ($this->day >= 21) && ($this->day <= 30) || ($this->month === self::MONTH_FEBRUARY) && ($this->day <= 19);
    }

    /**
     * Checks whether the sign is Pisces
     * 
     * @return boolean
     */
    public function isPisces()
    {
        return ($this->month === self::MONTH_FEBRUARY) && ($this->day >= 20) && ($this->day <= 30) || ($this->month === self::MONTH_MARCH) && ($this->day <= 20);
    }
}

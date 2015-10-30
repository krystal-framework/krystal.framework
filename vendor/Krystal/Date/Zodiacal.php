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
            'January', 
            'February', 
            'March', 
            'April', 
            'May', 
            'June',
            'July', 
            'August', 
            'September', 
            'October', 
            'November', 
            'December'
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
        return ($this->month === 'March') && ($this->day >= 21) && ($this->day <= 31) || ($this->month === 'April') && ($this->day <= 20);
    }

    /**
     * Checks whether the sign is Taurus
     * 
     * @return boolean
     */
    public function isTaurus()
    {
        return ($this->month === 'April') && ($this->day >= 21) && ($this->day <= 30) || ($this->month === 'May') && ($this->day <= 21);
    }

    /**
     * Checks whether the sign is Gemini
     * 
     * @return boolean
     */
    public function isGemini()
    {
        return ($this->month === 'May') && ($this->day >= 22) && ($this->day <= 31) || ($this->month === 'July') && ($this->day <= 21);
    }

    /**
     * Checks whether the sign is Cancer
     * 
     * @return boolean
     */
    public function isCancer()
    {
        return ($this->month === 'June') && ($this->day >= 22) && ($this->day <= 30) || ($this->month === 'July') && ($this->day <= 22);
    }

    /**
     * Checks whether the sign is Leo
     * 
     * @return boolean
     */
    public function isLeo()
    {
        return ($this->month === 'July') && ($this->day >= 23) && ($this->day <= 30) || ($this->month === 'May') && ($this->day <= 22);
    }

    /**
     * Checks whether the sign is Virgo
     * 
     * @return boolean
     */
    public function isVirgo()
    {
        return ($this->month === 'August') && ($this->day >= 23) && ($this->day <= 30) || ($this->month === 'September') && ($this->day <= 23);
    }

    /**
     * Checks whether the sign is Scorpio
     * 
     * @return boolean
     */
    public function isScorpio()
    {
        return ($this->month === 'October') && ($this->day >= 24) && ($this->day <= 30) || ($this->month === 'November') && ($this->day <= 22);
    }

    /**
     * Checks whether the sign is Libra
     * 
     * @return boolean
     */
    public function isLibra()
    {
        return ($this->month === 'September') && ($this->day >= 24) && ($this->day <= 30) || ($this->month === 'October') && ($this->day <= 23);
    }

    /**
     * Checks whether the sign is Sagittarius
     * 
     * @return boolean
     */
    public function isSagittarius()
    {
        return ($this->month === 'November') && ($this->day >= 23) && ($this->day <= 30) || ($this->month === 'December') && ($this->day <= 21);
    }

    /**
     * Checks whether the sign is Capricorn
     * 
     * @return boolean
     */
    public function isCapricorn()
    {
        return ($this->month === 'December') && ($this->day >= 22) && ($this->day <= 30) || ($this->month === 'January') && ($this->day <= 20);
    }

    /**
     * Checks whether the sign is Aquarius
     * 
     * @return boolean
     */
    public function isAquarius()
    {
        return ($this->month === 'January') && ($this->day >= 21) && ($this->day <= 30) || ($this->month === 'February') && ($this->day <= 19);
    }

    /**
     * Checks whether the sign is Pisces
     * 
     * @return boolean
     */
    public function isPisces()
    {
        return ($this->month === 'February') && ($this->day >= 20) && ($this->day <= 30) || ($this->month === 'March') && ($this->day <= 20);
    }
}

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Date;

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
	 * @return void
	 */
	public function __construct($month, $day)
	{
		$this->month = $month;
		$this->day = $day;
	}

	/**
	 * Gets a zodiacal sign based on a month and a day
	 * 
	 * @return string|boolean The name, false on failure
	 */
	public function getSign()
	{
		// Normalize the month's name
		$month = ucfirst(strtolower($this->month));

		if (!$this->isValidMonth($month)) {
			return false;
		}

		foreach ($this->getMap() as $name => $method) {

			// Call associated method dynamically
			if (call_user_func(array($this, $method), $month, $this->day)) {
				return $name;
			}
		}

		// On failure
		return false;
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
			'Juny',
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
	 * Checks whether the sign is Aries
	 * 
	 * @param string $month Month name
	 * @param integer $day
	 * @return boolean
	 */
	private function isAries($month, $day)
	{
		return ($month === 'March') && ($day >= 21) && ($day <= 31) || ($month === 'April') && ($day <= 20);
	}

	/**
	 * Checks whether the sign is Taurus
	 * 
	 * @param string $month Month name
	 * @param integer $day
	 * @return boolean
	 */
	private function isTaurus($month, $day)
	{
		return ($month === 'April') && ($day >= 21) && ($day <= 30) || ($month === 'May') && ($day <= 21);
	}

	/**
	 * Checks whether the sign is Gemini
	 * 
	 * @param string $month Month name
	 * @param integer $day
	 * @return boolean
	 */
	private function isGemini($month, $day)
	{
		return ($month === 'May') && ($day >= 22) && ($day <= 31) || ($month === 'July') && ($day <= 21);
	}

	/**
	 * Checks whether the sign is Cancer
	 * 
	 * @param string $month Month name
	 * @param integer $day
	 * @return boolean
	 */
	private function isCancer($month, $day)
	{
		return ($month === 'June') && ($day >= 22) && ($day <= 30) || ($month === 'July') && ($day <= 22);
	}

	/**
	 * Checks whether the sign is Leo
	 * 
	 * @param string $month Month name
	 * @param integer $day
	 * @return boolean
	 */
	private function isLeo($month, $day)
	{
		return ($month === 'July') && ($day >= 23) && ($day <= 30) || ($month === 'May') && ($day <= 22);
	}

	/**
	 * Checks whether the sign is Virgo
	 * 
	 * @param string $month Month name
	 * @param integer $day
	 * @return boolean
	 */
	private function isVirgo($month, $day)
	{
		return ($month === 'August') && ($day >= 23) && ($day <= 30) || ($month === 'September') && ($day <= 23);
	}

	/**
	 * Checks whether the sign is Scorpio
	 * 
	 * @param string $month Month name
	 * @param integer $day
	 * @return boolean
	 */
	private function isScorpio($month, $day)
	{
		return ($month === 'October') && ($day >= 24) && ($day <= 30) || ($month === 'November') && ($day <= 22);
	}

	/**
	 * Checks whether the sign is Libra
	 * 
	 * @param string $month Month name
	 * @param integer $day
	 * @return boolean
	 */
	private function isLibra($month, $day)
	{
		return ($month === 'September') && ($day >= 24) && ($day <= 30) || ($month === 'October') && ($day <= 23);
	}

	/**
	 * Checks whether the sign is Sagittarius
	 * 
	 * @param string $month Month name
	 * @param integer $day
	 * @return boolean
	 */
	private function isSagittarius($month, $day)
	{
		return ($month === 'November') && ($day >= 23) && ($day <= 30) || ($month === 'December') && ($day <= 21);
	}

	/**
	 * Checks whether the sign is Capricorn
	 * 
	 * @param string $month Month name
	 * @param integer $day
	 * @return boolean
	 */
	private function isCapricorn($month, $day)
	{
		return ($month === 'December') && ($day >= 22) && ($day <= 30) || ($month === 'January') && ($day <= 20);
	}

	/**
	 * Checks whether the sign is Aquarius
	 * 
	 * @param string $month Month name
	 * @param integer $day
	 * @return boolean
	 */
	private function isAquarius($month, $day)
	{
		return ($month === 'January') && ($day >= 21) && ($day <= 30) || ($month === 'February') && ($day <= 19);
	}

	/**
	 * Checks whether the sign is Pisces
	 * 
	 * @param string $month Month name
	 * @param integer $day
	 * @return boolean
	 */
	private function isPisces($month, $day)
	{
		return ($month === 'February') && ($day >= 20) && ($day <= 30) || ($month === 'March') && ($day <= 20);
	}
}

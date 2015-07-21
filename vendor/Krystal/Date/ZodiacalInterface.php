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

/**
 * The detector is based on these assumptions:
 * 
 * Aries (Mar. 21- April 20)
 * Taurus (Apr. 21- may 21)
 * Gemini (May 22-June 21)
 * Cancer (June 22-July 22)
 * Leo (July 23-Aug 22)
 * Virgo (Aug. 23 -Sept. 23)
 * Libra (Sept. 24 -Oct. 23)
 * Scorpio (Oct. 24 - Nov. 22)
 * Sagittarius (Nov. 23 -Dec. 21)
 * Capricorn (Dec 22.- Jan. 20)
 * Aquarius (Jan. 21.- Feb. 19)
 * Pisces (Feb. 20-Mar. 20)
 * 
 */

interface ZodiacalInterface
{
	/**
	 * Manualy checks whether $sign equals to calculated one
	 * 
	 * @param string $sign
	 * @return boolean
	 */
	public function is($sign);

	/**
	 * Gets a zodiacal sign based on a month and a day
	 * 
	 * @return string|boolean The name, false on failure
	 */
	public function getSign();

	/**
	 * Checks whether the sign is Aries
	 * 
	 * @return boolean
	 */
	public function isAries();

	/**
	 * Checks whether the sign is Taurus
	 * 
	 * @return boolean
	 */
	public function isTaurus();

	/**
	 * Checks whether the sign is Gemini
	 * 
	 * @return boolean
	 */
	public function isGemini();

	/**
	 * Checks whether the sign is Cancer
	 * 
	 * @return boolean
	 */
	public function isCancer();

	/**
	 * Checks whether the sign is Leo
	 * 
	 * @return boolean
	 */
	public function isLeo();

	/**
	 * Checks whether the sign is Virgo
	 * 
	 * @return boolean
	 */
	public function isVirgo();

	/**
	 * Checks whether the sign is Scorpio
	 * 
	 * @return boolean
	 */
	public function isScorpio();

	/**
	 * Checks whether the sign is Libra
	 * 
	 * @return boolean
	 */
	public function isLibra();

	/**
	 * Checks whether the sign is Sagittarius
	 * 
	 * @return boolean
	 */
	public function isSagittarius();

	/**
	 * Checks whether the sign is Capricorn
	 * 
	 * @return boolean
	 */
	public function isCapricorn();

	/**
	 * Checks whether the sign is Aquarius
	 * 
	 * @return boolean
	 */
	public function isAquarius();

	/**
	 * Checks whether the sign is Pisces
	 * 
	 * @return boolean
	 */
	public function isPisces();
}

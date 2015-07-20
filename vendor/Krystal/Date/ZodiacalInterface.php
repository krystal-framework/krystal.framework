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
	 * Gets a zodiacal sign based on a month and a day
	 * 
	 * @return string|boolean The name, false on failure
	 */
	public function getSign();
}

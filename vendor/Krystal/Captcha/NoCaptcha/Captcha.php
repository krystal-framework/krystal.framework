<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Captcha\NoCaptcha;

use Krystal\Captcha\CaptchaInterface;

final class Captcha implements CaptchaInterface
{
	/**
	 * State initialization
	 * 
	 * @return void
	 */
	public function __construct()
	{
	}

	/**
	 * Returns error message
	 * 
	 * @return string
	 */
	public function getError()
	{
	}

	/**
	 * Renders a widget
	 * 
	 * @return void
	 */
	public function render()
	{
		require(__DIR__ . '/widget.phtml');
	}

	/**
	 * Checks whether user's response is valid
	 * 
	 * @param string $answer
	 * @return boolean
	 */
	public function isValid($answer)
	{
		
	}
}

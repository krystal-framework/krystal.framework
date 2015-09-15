<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validate\Input\Constraint;

use Krystal\Captcha\CaptchaInterface;

final class Captcha extends AbstractConstraint
{
	/**
	 * {@inheritDoc}
	 */
	protected $message = 'CAPTCHA is invalid';

	/**
	 * CAPTCHA service
	 * 
	 * @var \Krystal\Captcha\CaptchaInterface
	 */
	private $captcha;

	/**
	 * State initialization
	 * 
	 * @param \Krystal\Captcha\CaptchaInterface $captcha CAPTCHA service
	 * @return void
	 */
	public function __construct(CaptchaInterface $captcha)
	{
		$this->captcha = $captcha;
	}

	/**
	 * {@inheritDoc}
	 */
	public function isValid($input)
	{
		if ($this->captcha->isValid($input)) {
			return true;
		} else {

			$this->violate($this->message);
			return false;
		}
	}
}

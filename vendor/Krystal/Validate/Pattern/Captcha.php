<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validate\Pattern;

use Krystal\Captcha\CaptchaInterface;

final class Captcha extends AbstractPattern
{
	/**
	 * CAPTCHA service
	 * 
	 * @var \Krystal\Captcha\CaptchaInterface
	 */
	private $captcha;

	/**
	 * State initialization
	 * 
	 * @param \Krystal\Captcha\CaptchaInterface $captcha
	 * @return void
	 */
	public function __construct(CaptchaInterface $captcha)
	{
		$this->captcha = $captcha;
		parent::__construct();
	}

	/**
	 * {@inheritDoc}
	 */
	public function getDefinition()
	{
		return $this->getWithDefaults(array(
			'required' => true,
			'rules' => array(
				'NotEmpty' => array(
					'message' => 'CAPTCHA can not be empty'
				),
				'Captcha' => array(
					// This will pass \Krystal\Captcha\CaptchaInterface\'s instance to constraint constructor
					'value' => $this->captcha,
					'message' => 'Invalid verification code'
				)
			)
		));
	}
}

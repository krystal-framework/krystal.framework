<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Captcha\Standard\Image;

final class ImageResponse implements ImageResponseInterface
{
	/**
	 * Image type
	 * 
	 * @var string
	 */
	private $type;

	/**
	 * State initialization
	 * 
	 * @param string $type
	 * @return void
	 */
	public function __construct($type)
	{
		$this->type = $type;
	}

	/**
	 * Sends appropriate headers
	 * 
	 * @return void
	 */
	public function send()
	{
		$headers = array(
			'Pragma: public',
			'Expires: 0',
			'Cache-Control: must-revalidate, post-check=0, pre-check=0',
			'Content-Transfer-Encoding: binary',
			'Content-type: ' . $this->type
		);

		foreach ($headers as $header) {
			header($header);
		}
	}
}

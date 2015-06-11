<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validate\Renderer;

class MessagesOnly extends Standard
{
	/**
	 * Target template
	 * 
	 * @var string
	 */
	private $template;

	/**
	 * State initialization
	 * 
	 * @param string $template
	 * @return void
	 */
	public function __construct($template = null)
	{
		$this->template = $template;
	}

	/**
	 * {@inheritDoc}
	 */
	public function render(array $errors)
	{
		$messages = parent::render($errors);
		$result = array();

		foreach ($messages as $key => $array) {
			foreach ($array as $index => $message) {
				array_push($result, $message);
			}
		}

		return $result;
	}
}

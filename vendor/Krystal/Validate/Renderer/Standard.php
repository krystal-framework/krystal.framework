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

/**
 * This includes keys and values
 */
class Standard implements RendererInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function render(array $errors)
	{
		$messages = array();

		foreach ($errors as $target => $array) {
			// Ensure target exists, before appending a value
			if (!isset($messages[$target])) {
				$messages[$target] = array();
			}

			foreach ($array as $index => $messageArray) {
				array_push($messages[$target], $messageArray[0]);
			}
		}

		return $messages;
	}
}

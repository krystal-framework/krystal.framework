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

final class StandardJson extends Standard implements RendererInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function render(array $errors)
	{
		return json_encode(parent::render($errors));
	}
}

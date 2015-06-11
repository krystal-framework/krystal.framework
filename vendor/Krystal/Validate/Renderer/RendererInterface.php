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

interface RendererInterface
{
	/**
	 * Prepare error messages for more appropriate format
	 * 
	 * @param array $errors
	 * @return array
	 */
	public function render(array $errors);
}

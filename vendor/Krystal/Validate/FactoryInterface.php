<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validate;

use Krystal\Validate\Renderer\RendererInterface;

interface FactoryInterface
{
	/**
	 * Sets or overrides default renderer
	 * 
	 * @param \Krystal\Validate\Renderer\RendererInterface $renderer
	 * @return void
	 */
	public function setRenderer(RendererInterface $renderer);

	/**
	 * Builds the instance
	 * 
	 * @param array $validators
	 * @return \Krystal\Validate\ValidatorChain
	 */
	public function build(array $validators);
}

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Form\Element;

use Krystal\Form\NodeElement;

class Number
{
	/**
	 * {@inheritDoc}
	 */
	public function getElement(array $attrs)
	{
		$node = new NodeElement();

		return $node->openTag('number')
					->addAttributes($attrs)
					->finalize(true)
					->render();
	}
}

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Form\Element;

use Krystal\Form\NodeElement;

final class Image implements FormElementInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function render(array $attrs)
	{
		$node = new NodeElement();

		return $node->openTag('img')
					->addAttributes($attrs)
					->finalize(true)
					->render();
	}
}

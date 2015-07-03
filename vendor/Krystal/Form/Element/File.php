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

final class File implements FormElementInterface
{
	/**
	 * Builds the element
	 * 
	 * @return \Krystal\Form\Element\File
	 */
	public static function factory()
	{
	}

	/**
	 * {@inheritDoc}
	 */
	public function render(array $attrs)
	{
		$attrs['type'] = 'file';

		$node = new NodeElement();

		return $node->openTag('input')
					->addAttributes($attrs)
					->finalize(true)
					->render();
	}
}

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

final class Textarea implements FormElementInterface
{
	/**
	 * Current content
	 * 
	 * @var string
	 */
	private $text;

	/**
	 * State initialization
	 * 
	 * @param string $text
	 * @return void
	 */
	public function __construct($text = null)
	{
		$this->text = $text;
	}

	/**
	 * Builds the element
	 * 
	 * @return \Krystal\Form\Element\Textarea
	 */
	public static function factory()
	{
	}

	/**
	 * {@inheritDoc}
	 */
	public function render(array $attrs)
	{
		$node = new NodeElement();
		$node->openTag('textarea')
		     ->addAttributes($attrs)
			 ->finalize()
			 ->setText($this->text)
			 ->closeTag();
			 
		return $node->render();
	}
}

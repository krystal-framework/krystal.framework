<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Serializer;

final class NativeSerializer extends AbstractSerializer
{
	/**
	 * {@inheritDoc}
	 */
	public function isSerialized($string)
	{
		return $string == serialize(false) || @unserialize($string) !== false;
	}

	/**
	 * {@inheritDoc}
	 */
	public function serialize($var)
	{
		return serialize($var);
	}

	/**
	 * {@inheritDoc}
	 */
	public function unserialize($string)
	{
		return unserialize($string);
	}
}

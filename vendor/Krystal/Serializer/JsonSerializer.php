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

final class JsonSerializer extends AbstractSerializer
{
	/**
	 * {@inheritDoc}
	 */
	public function serialize($var)
	{
		return json_encode($var);
	}

	/**
	 * {@inheritDoc}
	 */
	public function unserialize($serialized)
	{
		return json_decode($serialized, true);
	}

	/**
	 * {@inheritDoc}
	 */
	public function isSerialized($var)
	{
		json_decode($var);
		return (json_last_error() === \JSON_ERROR_NONE);
	}
}

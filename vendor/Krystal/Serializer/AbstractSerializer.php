<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Serializer;

abstract class AbstractSerializer
{
	/**
	 * Builds a hash of an array
	 * 
	 * @param array $array
	 * @return string
	 */
	public function buildHash(array $array)
	{
		return md5(serialize($array));
	}

	/**
	 * Checks whether given $var can be serialized
	 * 
	 * @param mixed $var
	 * @return boolean, TRUE if it is, FALSE otherwise
	 */
	public function isSerializeable($var)
	{
		return in_array(gettype($var), array('array', 'object'));
	}

	/**
	 * Checks whether given string is serialized
	 * 
	 * @param string $string
	 * @return boolean TRUE if serialized, FALSE otherwise
	 */
	abstract public function isSerialized($string);

	/**
	 * Serializes a variable
	 * 
	 * @param object|array $var
	 * @return string
	 */
	abstract public function serialize($var);

	/**
	 * Attempts to unserialize a string
	 * 
	 * @param string $serialized Serialized string
	 * @return object|array
	 */
	abstract public function unserialize($serialized);
}

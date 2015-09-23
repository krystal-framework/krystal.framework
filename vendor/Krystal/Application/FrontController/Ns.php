<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\FrontController;

/**
 * Namespace helper
 */
class Ns
{
	/**
	 * Normalizes a path
	 * 
	 * @param string $target
	 * @return string
	 */
	public static function normalize($target)
	{
		return str_replace('/', '\\', $target);
	}

	/**
	 * Extracts vendor's namespace
	 * 
	 * @param string $compliant
	 * @return string
	 */
	public static function extractVendorNs($compliant)
	{
		// To work with a string easily, it's better to turn it into an array
		$array = explode('/', $compliant);

		foreach ($array as $key => $value) {
			if ($value == '') {
				return $array[1];
			}
		}

		return $array[0];
	}
}

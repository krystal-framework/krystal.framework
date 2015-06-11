<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\Model;

use Krystal\Http\FileTransfer\Filter\NameFilter;
use Krystal\Http\FileTransfer\Filter\Type\Unique as UniqueType;

abstract class AbstractManager
{
	/**
	 * Filters file input names
	 * 
	 * @param array $files
	 * @return void
	 */
	final protected function filterFileInput(array $files)
	{
		// Cache method calls
		static $filter = null;

		if ($filter === null) {
			$filter = new NameFilter(new UniqueType());
		}

		// By reference anyway
		$filter->filter($files);
	}

	/**
	 * Converts a raw array to entity
	 * 
	 * @param array $array
	 * @return \Krystal\Stdlib\VirtualEntity
	 */
	protected function toEntity(array $array)
	{
	}

	/**
	 * Converts an array to object
	 * 
	 * @param array $array
	 * @return array
	 */
	final protected function prepareResults(array $array)
	{
		if (!empty($array)) {
			$result = array();
			foreach ($array as $target) {
				array_push($result, $this->toEntity($target));
			}

			return $result;
		} else {
			return array();
		}
	}

	/**
	 * Prepares a result
	 * 
	 * @param mixed $result
	 * @return \Krystal\Stdlib\VirtualEntity|boolean
	 */
	final protected function prepareResult($result)
	{
		if ($result) {
			return $this->toEntity($result);
		} else {
			return false;
		}
	}
}

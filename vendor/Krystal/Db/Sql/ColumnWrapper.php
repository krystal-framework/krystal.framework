<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Db\Sql;

use InvalidArgumentException;

final class ColumnWrapper implements ColumnWrapperInterface
{
	/**
	 * Wraps a column on demand
	 * 
	 * @param string $target
	 * @return string
	 */
	public function wrap($target)
	{
		if (!$this->isWorth($target)) {
			return $target;
		}

		$wrapper = function($column) {
			return sprintf('`%s`', $column);
		};

		if (is_array($target)) {
			foreach($target as &$column) {
				$column = $wrapper($column);
			}

		} else if (is_string($target)) {
			$target = $wrapper($target);

		} else {
			throw new InvalidArgumentException(sprintf(
				'Unknown type for wrapping supplied "%s"', gettype($target)
			));
		}

		return $target;
	}

	/**
	 * Tells whether it's worth applying a wrapper
	 * 
	 * @return boolean
	 */
	private function isWorth($target)
	{
		return !(is_numeric($target) || $this->isColumn($target) || $this->isSqlFunction($target));
	}

	/**
	 * Checks whether string looks like a column with a dot
	 * 
	 * @param string $target
	 * @return boolean
	 */
	private function isColumn($target)
	{
		return strpos($target, '.') !== false;
	}

	/**
	 * Checks whether string looks like SQL function
	 * 
	 * @param string $target
	 * @return boolean
	 */
	private function isSqlFunction($target)
	{
		return strpos($target, '(') !== false || strpos($target, ')') !== false;
	}
}

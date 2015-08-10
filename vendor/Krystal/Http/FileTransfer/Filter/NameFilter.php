<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Http\FileTransfer\Filter;

use Krystal\Http\FileTransfer\Filter\Type\FilterTypeInterface;
use Krystal\Http\FileTransfer\Filter\Type;
use LogicException;

final class NameFilter implements FileInputFilerInterface
{
	/**
	 * Any compliant filter
	 * 
	 * @var \Krystal\Http\FileTransfer\Filter\Type\FilterTypeInterface
	 */
	private $filter;

	/**
	 * State initialization
	 * 
	 * @param \Krystal\Http\FileTransfer\Filter\Type\FilterTypeInterface $filter
	 * @return void
	 */
	public function __construct(FilterTypeInterface $filter)
	{
		$this->filter = $filter;
	}

	/**
	 * Builds an instance
	 * 
	 * @param string $filter
	 * @throws \LogicException If Unknown filter supplied
	 * @return \Krystal\Http\FileTransfer\Filter\NameFilter
	 */
	public static function factory($type = 'unique')
	{
		switch ($type) {
			case 'unique':
				$filter = new Type\Unique();
			break;

			default:
				throw new LogicException(sprintf('Unknown filter supplied "%s"', $type));
		}

		return new self($filter);
	}

	/**
	 * Filter names. This can be useful if we want to handle all names
	 * This is basically used to solve UTF-8 problems when files are broken right after uploading if they contain UTF-8 characters
	 * By applying some callback function that operates on all 'name' keys and returns modified value
	 * 
	 * @param array $files
	 * @return void
	 */
	public function filter(array $files)
	{
		foreach ($files as $fileBag) {
			
			$name = $this->filter->filter($fileBag->getName());
			$fileBag->setName($name);
		}
	}
}

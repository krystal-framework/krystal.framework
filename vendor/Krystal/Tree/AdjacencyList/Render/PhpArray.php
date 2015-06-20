<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Tree\AdjacencyList\Render;

final class PhpArray extends AbstractRenderer
{
	/**
	 * Target separator
	 * 
	 * @var string
	 */
	private $separator;

	/**
	 * Indicates level of recursive call
	 * 
	 * @var integer
 	 */
	private $level = 1;

	/**
	 * @var string
	 */
	private $nameKey;

	/**
	 * State initialization
	 * 
	 * @param string $separator
	 * @return void
	 */
	public function __construct($nameKey, $separator = '—')
	{
		$this->nameKey = $nameKey;
		$this->separator = $separator;
	}

	/**
	 * {@inheritDoc}
	 */
	public function render(array $data, $active = null, $parentId = 0)
	{
		$result = array();
		
		if (isset($data['parents'][$parentId])) {
			foreach ($data['parents'][$parentId] as $itemId) {
				
				$row = $data['items'][$itemId];
				
				// That's array's value
				$value = sprintf('%s %s', str_repeat($this->separator, $this->level - 1), $row[$this->nameKey]);
				$result[$row['id']] = $value;
				
				// subsequent items will be indented one level
				$this->level++;
				
				// Recursive call
				$result = ($result + $this->render($data, $active, $itemId));
				
				// recursive call has returned, so restore a level
				$this->level--;
			}
		}
		
		return $result;
	}
}
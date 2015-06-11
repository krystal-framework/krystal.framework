<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Tree\AdjacencyList;

class ChildrenParser
{
	/**
	 * Children key
	 * 
	 * @var string
	 */
	protected $childrenKey;

	/**
	 * Id key
	 * 
	 * @var string
	 */
	protected $idKey;

	/**
	 * Parent key
	 * 
	 * @var string
	 */
	protected $parentKey;

	/**
	 * State initialization
	 * 
	 * @param string $childrenKey
	 * @param string $parentKey
	 * @param string $idKey
	 * @return void
	 */
	public function __construct($childrenKey = 'children', $parentKey = 'parent_id', $idKey = 'id')
	{
		$this->childrenKey = $childrenKey;
		$this->parentKey = $parentKey;
		$this->idKey = $idKey;
	}

	/**
	 * Parses nested array with children
	 * 
	 * @param array $data
	 * @param string|integer $parentID
	 * @return array
	 */
	final protected function parseData(array $data, $parentID = 0)
	{
		$result = array();
		
		foreach ($data as $subArray) {
			$nested = array();
			
			if (isset($subArray[$this->childrenKey])) {
				// Recursive call
				$nested = $this->parseData($subArray[$this->childrenKey], $subArray[$this->idKey]);
			}
			
			$result[] = array(
				$this->idKey => $subArray[$this->idKey], 
				$this->parentKey => $parentID
			);
			
			$result = array_merge($result, $nested);
		}
		
		return $result;
	}
}

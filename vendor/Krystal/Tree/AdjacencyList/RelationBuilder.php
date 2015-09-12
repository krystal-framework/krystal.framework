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

final class RelationBuilder
{
	const TREE_PARAM_ID = 'id';
	const TREE_PARAM_PARENT_ID = 'parent_id';
	const TREE_PARAM_NAME = 'name';
	const TREE_PARAM_ITEMS = 'items';
	const TREE_PARAM_PARENTS = 'parents';

	/**
	 * Default columns
	 * 
	 * @var array
	 */
	private $options = array(
		'id' => self::TREE_PARAM_ID,
		'parent' => self::TREE_PARAM_PARENT_ID,
		'name' => self::TREE_PARAM_NAME
	);

	/**
	 * State initialization
	 * 
	 * @param array $options
	 * @return void
	 */
	public function __construct(array $options = array())
	{
		$this->options = array_merge($options, $this->options);
	}

	/**
	 * Builds a relational tree
	 * 
	 * @param array $data Raw data
	 * @return array
	 */
	public function build(array $data)
	{
		$relations = array(
			self::TREE_PARAM_ITEMS => array(),
			self::TREE_PARAM_PARENTS => array()
		);

		foreach ($data as $row) {
			$relations[self::TREE_PARAM_ITEMS][$row[self::TREE_PARAM_ID]] = $row;
			$relations[self::TREE_PARAM_PARENTS][$row[self::TREE_PARAM_PARENT_ID]][] = $row[self::TREE_PARAM_ID];
		}

		return $relations;
	}
}

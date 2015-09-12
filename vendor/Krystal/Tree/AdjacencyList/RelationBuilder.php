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

final class RelationBuilder implements RelationBuilderInterface
{
	const TREE_PARAM_ID = 'id';
	const TREE_PARAM_PARENT_ID = 'parent_id';
	const TREE_PARAM_NAME = 'name';
	const TREE_PARAM_ITEMS = 'items';
	const TREE_PARAM_PARENTS = 'parents';

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

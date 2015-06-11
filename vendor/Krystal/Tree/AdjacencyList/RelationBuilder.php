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
	/**
	 * Target options
	 * 
	 * @var array
	 */
	private $options = array(
		'id'	 => 'id',
		'parent' => 'parent_id',
		'name'	 => 'name'
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
	 * {@inheritDoc}
	 */
	public function build(array $data)
	{
		$relations = array(
			'items' => array(),
			'parents' => array()
		);
		
		foreach ($data as $row) {
			// Append actual data
			$relations['items'][$row['id']] = $row;
			
			// And relations now
			$relations['parents'][$row['parent_id']][] = $row['id']; 
		}
		
		return $relations;
	}
}

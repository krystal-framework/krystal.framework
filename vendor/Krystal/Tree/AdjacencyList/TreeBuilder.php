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

use Krystal\Tree\AdjacencyList\Render\AbstractRenderer;
use Closure;
use RuntimeException;

final class TreeBuilder implements TreeInterface
{
	/**
	 * Raw data (that usually comes from a select query)
	 * 
	 * @var array
	 */
	private $data = array();

	/**
	 * Render strategy implementation
	 * 
	 * @var \Krystal\Tree\AdjacencyList\Render\AbstractRenderer
	 */
	private $renderer;

	/**
	 * Map of relationships
	 * 
	 * @var array
	 */
	private $relations;

	/**
	 * State initialization
	 * 
	 * @param array $data Raw data
	 * @return void
	 */
	public function __construct(array $data)
	{
		$this->data = $data;
	}

	/**
	 * Defines global renderer strategy
	 * 
	 * @param \Krystal\Tree\AdjacencyList\Render\AbstractRenderer $renderer
	 * @return void
	 */
	public function setRenderer(AbstractRenderer $renderer)
	{
		$this->renderer = $renderer;
	}

	/**
	 * Applies user-defined callback function to each node
	 * 
	 * @param string $parentId
	 * @param \Closure $callback
	 */
	public function applyToChildNodes($parentId, Closure $callback)
	{
		$ids = $this->findChildNodeIds($parentId);

		// If there's at least one child id, then start working next
		if (!empty($ids)) {
			foreach ($ids as $id) {
				// Invoke a callback supplying a child's id
				$callback($id);
			}
		}

		return true;
	}

	/**
	 * Finds all child node ids
	 * 
	 * @param string $id Parent id
	 * @return array
	 */
	public function findChildNodeIds($parentId)
	{
		//@TODO Reverse order
		return $this->findChildNodeWithKey($parentId, RelationBuilder::TREE_PARAM_ID);
	}

	/**
	 * Finds all child nodes
	 * 
	 * @param array $id Child id
	 * @return array
	 */
	public function findParentNodesByChildId($id)
	{
		// To be returned
		$result = array();

		$rl = $this->getRelations();
		$data = $rl[RelationBuilder::TREE_PARAM_ITEMS];

		$current = $data[$id];
		$parentId = $current[RelationBuilder::TREE_PARAM_PARENT_ID];

		while (isset($data[$parentId])) {
			$current = $data[$parentId];
			$parentId = $current[RelationBuilder::TREE_PARAM_PARENT_ID];

			array_push($result, $current);
		}

		return $result;
	}

	/**
	 * Finds all matches
	 * This method is useful for making breadcrumbs
	 * 
	 * @param string $id Either parent or child id
	 * @return array
	 */
	public function findAll($id)
	{
		$result = array();
		$root = $this->findById($id);

		if ($root !== false) {
			$result = array_merge($result, array($root));
		}

		return array_merge($result, $this->findParentNodesByChildId($id));
	}

	/**
	 * Finds a node by its associated id
	 * 
	 * @param string $id
	 * @return array|boolean
	 */
	public function findById($id)
	{
		$result = array();
		$relations = $this->getRelations();

		$items = $relations[RelationBuilder::TREE_PARAM_ITEMS];

		if (isset($items[$id])) {
			return $items[$id];
		} else {
			return false;
		}
	}

	/**
	 * Renders an interface
	 * 
	 * @param \Krystal\Tree\AdjacencyList\Render\AbstractRenderer $renderer Any renderer which extends AbstractRenderer
	 * @param string $active Active item
	 * @return string
	 */
	public function render(AbstractRenderer $renderer = null, $active = null)
	{
		if (is_null($renderer)) {
			$renderer = $this->getRenderer();
		}

		return $renderer->render($this->getRelations(), $active);
	}

	/**
	 * Returns relations lazily
	 * 
	 * @return array
	 */
	private function getRelations()
	{
		if (is_null($this->relations)) {
			$builder = new RelationBuilder();
			$this->relations = $builder->build($this->data);
		}

		return $this->relations;
	}

	/**
	 * Returns renderer strategy
	 * 
	 * @throws \RuntimeException If global renderer isn't defined
	 * @return \Krystal\Tree\AdjacencyList\Render\AbstractRenderer 
	 */
	private function getRenderer()
	{
		if (is_null($this->renderer)) {
			throw new RuntimeException('You have to define a renderer');
		}

		return $this->renderer;
	}

	/**
	 * Finds all child nodes including a key
	 * 
	 * @param string $parentId
	 * @param string $key
	 * @return array
	 */
	private function findChildNodeWithKey($parentId, $key)
	{
		$result = array();
		$relations = $this->getRelations();

		if (isset($relations[RelationBuilder::TREE_PARAM_PARENTS][$parentId])) {
			foreach ($relations[RelationBuilder::TREE_PARAM_PARENTS][$parentId] as $id) {
				// Current found node
				$node = $relations[RelationBuilder::TREE_PARAM_ITEMS][$id][$key];

				$result = array_merge($result, $this->findChildNodeWithKey($id, $key));
				$result[] = $node;
			}
		}

		return $result;
	}
}

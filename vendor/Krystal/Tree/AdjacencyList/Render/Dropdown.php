<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Tree\AdjacencyList\Render;

use Krystal\Form\NodeElement;
use RuntimeException;

class Dropdown extends AbstractDropdown
{
	/**
	 * Title column
	 * 
	 * @var string
	 */
	protected $column;

	/**
	 * State initialization
	 * 
	 * @param string $column
	 * @param array $options
	 * @return void
	 */
	public function __construct($column, array $options = array())
	{
		$this->column = $column;
		$this->options = $options;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getChildOpener(array $row, array $parents, $active)
	{
		if (!isset($row[$this->column])) {
			throw new RuntimeException(sprintf('Unknown title column supplied "%s"', $this->column));
		}

		$li = new NodeElement();
		$li->openTag('li');

		// Is it active web page?
		if (isset($row['active']) && $row['active'] === true) {
			$li->addAttribute('class', 'active');
		}

		// If no URL, replace with # explicitly
		if (!isset($row['url'])) {
			$row['url'] = '#';
		}

		$a = new NodeElement();
		$a->openTag('a')
		  ->addAttribute('href', $row['url']);

		$a->setText($row[$this->column])
		  ->closeTag();

		$li->appendChild($a);

		return $li->render();
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getParentCloser()
	{
		$ul = new NodeElement();
		return $ul->closeTag('ul')
				  ->render();
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getChildCloser()
	{
		$li = new NodeElement();
		return $li->closeTag('li')
				  ->render();
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getFirstLevelParent()
	{
		$ul = new NodeElement();
		$ul->openTag('ul');

		// Check whether we have a class name
		if (isset($this->options['class']['base'])) {
			$ul->addAttribute('class', $this->options['class']['base']);
		}

		return $ul->finalize()
				  ->render();
	}

	/**
 	 * {@inheritDoc}
	 */
	protected function getNestedLevelParent()
	{
		$ul = new NodeElement();
		return $ul->openTag('ul')
				  ->finalize()
				  ->render();
	}	
}

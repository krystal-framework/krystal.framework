<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\View;

/* Internal service which abstracts output buffering in ViewManager */
final class TemplateView implements TemplateViewInterface
{
	/**
	 * Template variables
	 * 
	 * @var array
	 */
	private $variables = array();

	/**
	 * View manager
	 * 
	 * @var \Krystal\Application\View\ViewManagerInterface
	 */
	private $view;

	/**
	 * State initialization
	 * 
	 * @param \Krystal\Application\View\ViewManagerInterface $view
	 * @param array $variables
	 * @return void
	 */
	public function __construct(ViewManagerInterface $view, array $variables)
	{
		$this->view = $view;
		$this->variables = $variables;
	}

	/**
	 * Method overloading which redirects undefined calls to view's instance
	 * 
	 * @param string $method Undefined method's name
	 * @param array $args Arguments to be passed to that method
	 * @return mixed
	 */
	public function __call($method, array $args)
	{
		return call_user_func_array(array($this->view, $method), $args);
	}

	/**
	 * Returns content of glued layout and its fragment
	 * 
	 * @param string $layout Path to a layout
	 * @param string $fragment Path to a fragment
	 * @param string $variable Variable name which represents a fragment
	 * @return string
	 */
	public function getFileContentWithLayout($layout, $fragment, $variable)
	{
		// Include and parse fragment's template
		ob_start();
		extract($this->variables);
		include($fragment);

		// Save it into a variable
		$fragment = ob_get_clean();

		ob_start();

		// Append new variable to the global stack
		$this->variables[$variable] = $fragment;

		extract($this->variables);
		include($layout);

		return ob_get_clean();
	}

	/**
	 * Includes a file a returns its content as a string
	 * 
	 * @param string $file Path to the file
	 * @return string
	 */
	public function getFileContent($file)
	{
		ob_start();
		extract($this->variables, \EXTR_REFS);
		include($file);

		return ob_get_clean();
	}
}

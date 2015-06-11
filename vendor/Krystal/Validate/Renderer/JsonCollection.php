<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validate\Renderer;

final class JsonCollection extends Standard
{
	/**
	 * @var string
	 */
	private $template;

	/**
	 * State initialization
	 * 
	 * @param string $template Optional template
	 * @return void
	 */
	public function __construct($template = null)
	{
		$this->template = $template;
	}

	/**
	 * {@inheritDoc}
	 */
	public function render(array $errors)
	{
		$messages = parent::render($errors);

		// This is for returning
		$result = array(
			'messages' => array(),
			'names' => array()
		);

		foreach ($messages as $name => $messageCollection) {
			$result['messages'] = array_merge($messageCollection, $result['messages']);
			array_push($result['names'], $name);
		}
		
		if ($this->template !== null) {
			
			ob_start();
			$errors = $result['messages'];
			include($this->template);
			$content = ob_get_clean();
			
			// Override it now
			$result['messages'] = $content;
		}
		
		return json_encode($result);
	}
}
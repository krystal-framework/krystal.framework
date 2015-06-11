<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validate\Pattern;

final class PasswordConfirmation extends AbstractPattern
{
	/**
	 * Target password to compare against
	 * 
	 * @var string
	 */
	private $target;

	/**
	 * State initialization
	 * 
	 * @param array $overrides
	 * @param string $target Password confirmation
	 * @return void
	 */
	public function __construct(array $overrides = array(), $target)
	{
		parent::__construct($overrides);
		$this->target = $target;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getDefinition()
	{
		return $this->getWithDefaults(array(
			'required' => true,
			'rules' => array(
				'NotEmpty' => array(
					'message' => 'Password confirmation can not be empty'
				),
				'Identity' => array(
					'message' => 'Passwords do not match the same combination',
					// This is what to compare against
					'value' => $this->target
				)
			)
		));
	}
}
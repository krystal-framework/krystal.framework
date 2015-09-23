<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
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
	private $password;

	/**
	 * State initialization
	 * 
	 * @param string $target Password confirmation
	 * @param array $overrides
	 * @return void
	 */
	public function __construct($password, array $overrides = array())
	{
		parent::__construct($overrides);
		$this->password = $password;
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
					'value' => $this->password
				)
			)
		));
	}
}
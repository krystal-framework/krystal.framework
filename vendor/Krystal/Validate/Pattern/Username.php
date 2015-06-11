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

final class Username extends AbstractPattern
{
	/**
	 * {@inheritDoc}
	 */
	public function getDefinition()
	{
		return $this->prepare(array(
			'required' => true,
			'rules' => array(
				'NotEmpty' => array(
					'message' => 'Username can not be empty'
				),
				
				'MinLength' => array(
					'value' => 3,
					'message' => 'Username should contain at least 3 characters'
				),
				
				'MaxLength' => array(
					'value' => 15,
					'message' => 'Username cannot contain more than 15 characters'
				)
			)
		));
	}
}

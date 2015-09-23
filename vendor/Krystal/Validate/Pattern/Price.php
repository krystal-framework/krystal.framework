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

final class Price extends AbstractPattern
{
	/**
	 * {@inheritDoc}
	 */
	public function getDefinition()
	{
		return $this->getWithDefaults(array(
			'required' => true,
			'rules' => array(
				'NotEmpty' => array(
					'message' => 'Price can not be blank'
				),
				'Numeric' => array(
					'message' => 'Price must be numeric'
				),
				'GreaterThan' => array(
					'value' => 0,
					'message' => 'Price must be greater than 0'
				)
			)
		));
	}
}

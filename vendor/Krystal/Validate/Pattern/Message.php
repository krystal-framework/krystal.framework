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

final class Message extends AbstractPattern
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
					'message' => 'Message can not be blank'
				)
			)
		));
	}
}

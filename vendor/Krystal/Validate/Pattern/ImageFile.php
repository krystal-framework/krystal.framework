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

class ImageFile extends AbstractPattern
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
					'message' => 'Choice an image from your PC to upload'
				),
				'Extension' => array(
					'break' => false,
					'message' => 'Selected file does not seem to be a valid image',
					'value' => array(array('jpg', 'jpeg', 'png', 'gif'))
				),
			)
		));
	}
}

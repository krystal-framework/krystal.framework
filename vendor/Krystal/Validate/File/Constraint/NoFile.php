<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validate\File\Constraint;

/**
 * No file was uploaded. 
 */
final class NoFile extends AbstractConstraint
{
	/**
	 * {@inheritDoc}
	 */
	protected $message = 'No file was uploaded during the process';
	
	/**
	 * {@inheritDoc}
	 */
	public function isValid(array $files)
	{
		return true;
		// useless
		foreach ($files as $file) {
			if ($file->getError() == UPLOAD_ERR_NO_FILE) {
				$this->appendError(__CLASS__, $file);
			}
		}
		
		return !$this->hasErrors();
	}
}

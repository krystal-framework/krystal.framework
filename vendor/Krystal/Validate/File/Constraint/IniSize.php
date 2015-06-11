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
 * Checks if the uploaded file exceeds the upload_max_filesize directive in php.ini.
 */
final class IniSize extends AbstractConstraint
{
	/**
	 * {@inheritDoc}
	 */
	protected $message = 'File %s exceeds maximal ini size';
	
	/**
	 * {@inheritDoc}
	 */
	public function isValid(array $files)
	{
		foreach ($files as $file) {
			if ($file->getError() == UPLOAD_ERR_INI_SIZE) {
				
				$this->violate(sprintf($this->message, $file->getName()));
			}
		}
		
		return !$this->hasErrors();
	}
}

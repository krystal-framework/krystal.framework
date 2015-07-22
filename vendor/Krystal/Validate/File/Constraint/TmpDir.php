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
 * Missing a temporary folder. Introduced in PHP 4.3.10 and PHP 5.0.3
 */
final class TmpDir extends AbstractConstraint
{
	/**
	 * {@inheritDoc}
	 */
	protected $message = 'Could not write %s to temporary directory on the server';

	/**
	 * {@inheritDoc}
	 */
	public function isValid(array $files)
	{
		foreach ($files as $file) {
			if ($file->getError() == UPLOAD_ERR_NO_TMP_DIR) {
				$this->violate(sprintf($this->message, $file->getName()));
			}
		}

		return !$this->hasErrors();
	}
}

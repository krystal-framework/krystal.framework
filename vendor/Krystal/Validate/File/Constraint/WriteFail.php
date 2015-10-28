<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validate\File\Constraint;

/**
 * Failed to write file to disk. Introduced in PHP 5.1.0
 */
final class WriteFail extends AbstractConstraint
{
    /**
     * {@inheritDoc}
     */
    protected $message = 'Failed to write %s to the file-system';

    /**
     * {@inheritDoc}
     */
    public function isValid(array $files)
    {
        foreach ($files as $file) {
            if ($file->getError() == \UPLOAD_ERR_CANT_WRITE) {
                $this->violate(sprintf($this->message, $file->getName()));
            }
        }

        return !$this->hasErrors();
	}
}

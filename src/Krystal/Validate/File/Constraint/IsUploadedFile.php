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

final class IsUploadedFile extends AbstractConstraint
{
    /**
     * {@inheritDod}
     */
	protected $message = 'A file %s was not properly uploaded';

    /**
     * {@inhertitDoc}
     */
    public function isValid(array $files)
    {
        foreach ($files as $file) {
            if (!is_uploaded_file($file->getTmpName())) {
                $this->violate(sprintf($this->message, $file->getName()));
            }
        }

        return !$this->hasErrors();
	}
}

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
 * The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form. 
 */
final class FormSize extends AbstractConstraint
{
    /**
     * {@inheritDoc}
     */
    protected $message = 'File %s exceeds maximal allowed form size';

    /**
     * {@inheritDoc}
     */
    public function isValid(array $files)
    {
        foreach ($files as $file) {
            if ($file->getError() == UPLOAD_ERR_FORM_SIZE) {
                $this->violate(sprintf($this->message, $file->getName()));
            }
        }

        return !$this->hasErrors();
    }
}

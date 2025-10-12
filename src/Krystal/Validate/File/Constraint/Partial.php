<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validate\File\Constraint;

/**
 * The uploaded file was only partially uploaded. 
 */
final class Partial extends AbstractConstraint
{
    /**
     * {@inheritDoc}
     */
    protected $message = 'File %s was partially uploaded only';

    /**
     * {@inheritDoc}
     */
    public function isValid($files)
    {
        foreach ($files as $file) {
            if ($file->getError() == \UPLOAD_ERR_PARTIAL) {
                $this->violate(sprintf($this->message, $file->getName()));
            }
        }

        return !$this->hasErrors();
    }
}

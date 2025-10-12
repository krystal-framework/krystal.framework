<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validate\File\Constraint;

abstract class AbstractConstraint extends \Krystal\Validate\AbstractConstraint
{
    /**
     * Runs the validation against current constraint
     * 
     * @param array $files
     * @return boolean
     */
    abstract public function isValid($files);
}

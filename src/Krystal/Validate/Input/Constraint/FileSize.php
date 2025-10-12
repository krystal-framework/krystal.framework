<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validate\Input\Constraint;

final class FileSize extends AbstractConstraint
{
    /**
     * {@inheritDoc}
     */
    protected $message = 'Given file path does not match required size';

    /**
     * Desired file size in bytes
     * 
     * @var integer
     */
    private $size;

    /**
     * State initialization
     * 
     * @param integer|float $size Size in bytes
     * @return void
     */
    public function __construct($size)
    {
        $this->size = $size;
    }

    /**
     * {@inheritDoc}
     */
    public function isValid($target)
    {
        if (filesize($target) == $this->size) {
            return true;
        } else {
            $this->violate($this->message);
            return false;
        }
    }
}

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

final class Type extends AbstractConstraint
{
    /**
     * MIME-type to test against
     * 
     * @var string
     */
    private $type;

    /**
     * {@inheritDoc}
     */
    protected $message = 'File %s contains invalid MIME-type';

    /**
     * State initialization
     * 
     * @param string $type
     * @return void
     */
    public function __construct($type)
    {
        $this->type = $type;
    }

	/**
	 * {@inheritDoc}
	 */
    public function isValid(array $files)
    {
        foreach ($files as $file) {
            if ($file->getType() != $this->type) {
                $this->violate(sprintf($this->message, $file->getName()));
            }
        }

        return !$this->hasErrors();
    }
}

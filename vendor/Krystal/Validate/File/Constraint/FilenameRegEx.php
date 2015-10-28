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

final class FilenameRegEx extends AbstractConstraint
{
    /**
     * RegEx pattern to match against
     * 
     * @var string
     */
    private $pattern;

    /**
     * {@inheritDoc}
     */
    protected $message = 'Filename %s does not match a regex';

    /**
     * State initialization
     * 
     * @param string $pattern RegEx pattern
     * @return void
     */
    public function __construct($pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * {@inheritDoc}
     */
    public function isValid(array $files)
    {
        foreach ($files as $file) {
            // @ - because the RegEx pattern itself could be wrong and issue E_WARNING
            if (!@preg_match($this->pattern, $file->getName())) {
                $this->violate(sprintf($this->message, $file->getName()));
            }
        }

        return !$this->hasErrors();
	}
}

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validate\File\Constraint;

final class FilenameMaxLength extends AbstractConstraint
{
    /**
     * Maximal allowed filename length
     * 
     * @var integer
     */
    private $maxLength;

    /**
     * {@inheritDoc}
     */
    protected $message = 'Filename %s exceeds maximal length';

    /**
     * State initialization
     * 
     * @param integer $maxLength
     * @return void
     */
    public function __construct($maxLength = 255)
    {
        $this->maxLength = $maxLength;
    }

    /**
     * {@inheritDoc}
     */
    public function isValid($files)
    {
        foreach ($files as $file) {
            if ($this->isNameLong($file->getName())) {
                $this->violate(sprintf($this->message, $file->getName()));
            }
        }

        return !$this->hasErrors();
    }

    /**
     * Checks whether filename is long
     * 
     * @param string $filename
     * @return boolean
     */
    private function isNameLong($filename)
    {
        return mb_strlen($filename, 'UTF-8') > $this->maxLength;
    }
}

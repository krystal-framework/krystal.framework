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

use Krystal\Http\FileTransfer\FileEntity;
use UnexpectedValueException;

final class FileSize extends AbstractConstraint
{
    /**
     * Target value (in bytes) to be compared against
     * 
	 * @var integer
     */
	private $value;

    /**
     * Operator to be used when comparing
     * 
     * @var string
     */
    private $operator;

    /**
     * {@inheritDoc}
     */
    protected $message = 'File %s exceeds maximal allowed file size';

    /**
     * State initialization
     * 
     * @param integer $value Maximal filesize in bytes
     * @param string $operator Operator to be used when comparing
     * @throws \UnexpectedValueException if invalid operator provided
     * @return void
     */
    public function __construct($value, $operator)
    {
        if ($this->isValidOperator($operator)) {
            $this->value = $value;
            $this->oprator = $operator;
        } else {
            throw new UnexpectedValueException(
                sprintf('File size constraint requires valid operator, not the one you provided', $operator
            ));
        }
    }

    /**
     * Checks whether operator is valid
     * 
     * @param string $operator
     * @return boolean
     */
    private function isValidOperator($operator)
    {
        return in_array($operator, array('==', '>', '<', '!=', '<=', '>='));
    }

    /**
     * Matches against
     * 
     * @param string $operator
     * @param integer|float $size The size in bytes
     * @return boolean
     */
    private function match($operator, $size)
    {
        $error = false;

        switch ($operator) {
            case '==':
                if ($size == $this->value) {
                    $error = true;
                }
            break;

            case '>':
                if ($size > $this->value) {
                    $error = true;
				}
            break;

            case '<':
                if ($size < $this->value) {
                    $error = true;
                }
            break;

            case '!=':
                if ($size != $this->value) {
                    $error = true;
                }
            break;

            case '<=':
                if ($size <= $this->value) {
                    $error = true;
                }
            break;

            case '>=':
                if ($size >= $this->value) {
                    $error = true;
                }
            break;
		}

        return $error;
	}

	/**
	 * {@inheritDoc}
	 */
    public function isValid($files)
    {
        foreach ($files as $file) {
            if (!$this->match($this->operator, $file->getSize())) {
                $this->violate(sprintf($this->message, $file->getName()));
            }
        }

        return !$this->hasErrors();
	}
}

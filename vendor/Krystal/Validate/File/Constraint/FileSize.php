<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
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
	 * Class initialization
	 * 
	 * @param integer $max Maximal filesize
	 * @param string $operator Operator to be used when comparing
	 * @return void
	 */
	public function __construct($value, $operator)
	{
		if ($this->isOperator($operator)) {

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
	private function isOperator($operator)
	{
		return in_array($operator, array('==', '>', '<', '!=', '<=', '>='));
	}

	/**
	 * Matches against
	 * 
	 * @param string $operator
	 * @param \Krystal\Http\FileTransfer\FileEntity $file
	 * @return boolean
	 */
	private function match($operator, FileEntity $file)
	{
		$error = false;

		switch ($operator) {
			case '==':
				if ($file->getSize() == $this->value) {
					$error = true;
				}
			break;

			case '>':
				if ($file->getSize() > $this->value) {
					$error = true;
				}
			break;

			case '<':
				if ($file->getSize() < $this->value) {
					$error = true;
				}
			break;

			case '!=':
				if ($file->getSize() != $this->value) {
					$error = true;
				}
			break;

			case '<=':
				if ($file->getSize() <= $this->value) {
					$error = true;
				}
			break;

			case '>=':
				if ($file->getSize() >= $this->value) {
					$error = true;
				}
			break;
		}

		return $error;
	}

	/**
	 * {@inheritDoc}
	 */
	public function isValid(array $files)
	{
		foreach ($files as $file) {
			if (!$this->match($this->operator, $file)) {
				$this->violate(sprintf($this->message, $file->getName()));
			}
		}

		return !$this->hasErrors();
	}
}

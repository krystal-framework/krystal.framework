<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validate;

interface ChainInterface
{
	/**
	 * Return error messages from all attached validators
	 * 
	 * @return array
	 */
	public function getErrors();

	/**
	 * Runs the validation against all defined validators
	 * 
	 * @return boolean
	 */
	public function isValid();

	/**
	 * Adds a validator
	 * 
	 * @param Validateable $validator
	 * @return void
	 */
	public function addValidators($validators);
}

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validate\Input\Constraint;

/**
 * You might wonder what's the point of extending AbstractConstraint in validation root directory
 * Well, the point of this is to not import each time a new constraint is added, so that use statement can be omitted
 */
abstract class AbstractConstraint extends \Krystal\Validate\AbstractConstraint
{
	/**
	 * Runs a constraint against target string
	 * 
	 * @param string $target
	 * @return boolean
	 */
	abstract public function isValid($target);
}

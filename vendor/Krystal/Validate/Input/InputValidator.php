<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validate\Input;

use Krystal\Validate\DefinitionParser;
use Krystal\Validate\Input\ConstraintFactory;
use Krystal\Validate\AbstractValidator;

final class InputValidator extends AbstractValidator
{
	/**
	 * Builds an instance
	 * 
	 * @param array $source
	 * @param array $definitions
	 * @param Translator $translator
	 * @return InputValidator
	 */
	public static function factory(array $source, array $definitions, $translator)
	{
		return new self($source, $definitions, new DefinitionParser(new ConstraintFactory()), $translator);
	}
}
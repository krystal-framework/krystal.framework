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

final class FilenameRegEx extends AbstractConstraint
{
	/**
	 * RegEx pattern to test against
	 * 
	 * @var string
	 */
	private $pattern;

	/**
	 * {@inheritDoc}
	 */
	protected $message = 'Filename %s does not match a regex';

	/**
	 * Class initialization
	 * 
	 * @param string $pattern
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
			// @ - because pattern could be wrong and an E_WARNING will be issued
			if (!@preg_match($this->pattern, $file->getName())) {
				$this->violate(sprintf($this->message, $file->getName()));
			}
		}

		return !$this->hasErrors();
	}
}

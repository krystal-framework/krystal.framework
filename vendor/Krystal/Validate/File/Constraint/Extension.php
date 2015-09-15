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

final class Extension extends AbstractConstraint
{
	/**
	 * Files with given extensions will be allowed to upload
	 * 
	 * @var array
	 */
	private $extensions = array();

	/**
	 * {@inheritDoc}
	 */
	protected $message = 'File %s contains wrong extension';

	/**
	 * State initialization
	 * 
	 * @param array $extension Valid extensions
	 * @return void
	 */
	public function __construct(array $extensions)
	{
		$this->extensions  = $extensions;
	}

	/**
	 * {@inheritDoc}
	 */
	public function isValid(array $files)
	{
		foreach ($files as $file) {
			if (!$this->hasValidExtension($file->getName())) {
				$this->violate(sprintf($this->message, $file->getName()));
				return false;
			}
		}

		return true;
	}

	/**
	 * Whether an extension belongs in collection
	 * 
	 * @param string $filename
	 * @return boolean True if has, False otherwise
	 */
	private function hasValidExtension($filename)
	{
		// Current extension
		$extension = mb_strtolower(pathinfo($filename, \PATHINFO_EXTENSION), 'UTF-8');

		// Make sure all extensions are in lowercase
		foreach ($this->extensions as &$expected) {
			$expected = mb_strtolower($expected, 'UTF-8');
		}

		return in_array($extension, $this->extensions);
	}
}

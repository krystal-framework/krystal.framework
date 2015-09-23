<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validate\Pattern;

final class DateFormat extends AbstractPattern
{
	/**
	 * The compliant date itself
	 * 
	 * @var string
	 */
	private $format;

	/**
	 * State initialization
	 * 
	 * @param string $format Date format
	 * @param array $overrides
	 * @return void
	 */
	public function __construct($format, array $overrides = array())
	{
		$this->format = $format;
		parent::__construct($overrides);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getDefinition()
	{
		return $this->getWithDefaults(array(
			'required' => true,
			'rules' => array(
				'NotEmpty' => array(
					'message' => 'Date format can not be empty'
				),
				'DateFormatMatch' => array(
					'value' => $this->format
				)
			)
		));
	}
}

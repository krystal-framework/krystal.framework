<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Db\Sql;

final class RawSqlFragment implements RawSqlFragmentInterface
{
	/**
	 * A fragment itself
	 * 
	 * @var string
	 */
	private $fragment;

	/**
	 * State initialization
	 * 
	 * @param string $fragment
	 * @return void
	 */
	public function __construct($fragment)
	{
		$this->fragment = $fragment;
	}

	/**
	 * Returns defined fragment
	 * 
	 * @return string
	 */
	public function getFragment()
	{
		return $this->fragment;
	}
}

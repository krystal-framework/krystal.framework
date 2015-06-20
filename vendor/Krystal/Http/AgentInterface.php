<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Http;

interface AgentInterface
{
	/**
	 * Return robots
	 * 
	 * @return array
	 */
	public function getRobots();

	/**
	 * Return browsers
	 * 
	 * @return array
	 */
	public function getBrowsers();

	/**
	 * Return platforms
	 * 
	 * @return array
	 */
	public function getPlatforms();	
}
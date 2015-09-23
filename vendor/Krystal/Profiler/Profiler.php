<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Profiler;

final class Profiler implements ProfilerInterface
{
	/**
	 * Timer for counting script's taken time
	 * 
	 * @var \Krystal\Profiler\Timer
	 */
	private $timer;

	/**
	 * State initialization
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->timer = new Timer();
	}

	/**
	 * Returns taken time
	 * 
	 * @return float
	 */
	public function getTakenTime()
	{
		return $this->timer->getSummary();
	}

	/**
	 * Returns memory usage
	 * 
	 * @return string
	 */
	public function getMemoryUsage()
	{
		return Memory::getUsage();
	}
}

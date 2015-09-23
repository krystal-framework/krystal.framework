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

use LogicException;

final class Timer implements TimerInterface
{
	/**
	 * Initial values
	 * 
	 * @var integer
	 */
	private $start, $end;

	/**
	 * State initialization
	 * 
	 * @param boolean $auto Whether to automatically start the time on initialization
	 * @return void
	 */
	public function __construct($auto = true)
	{
		if ($auto === true) {
			$this->start();
		}
	}

	/**
	 * Starts a timer
	 * 
	 * @return void
	 */
	public function start()
	{
		$this->start = microtime(true);
	}

	/**
	 * Stops a times
	 * 
	 * @return void
	 */
	private function stop()
	{
		$this->end = microtime(true);
	}

	/**
	 * Retrieves summary time
	 * 
	 * @throws \LogicException if timer was not started
	 * @return string
	 */
	public function getSummary()
	{
		$this->stop();

		if ($this->start !== null) {
			$summary = $this->end - $this->start;
			$summary = round($summary, 2);
			
			return $summary;

		} else {
			throw new LogicException('Timer was not started');
		}
	}

	/**
	 * Resets the timer
	 * 
	 * @return void
	 */
	public function reset()
	{
		$this->start = 0;
		$this->end	 = 0;
	}
}

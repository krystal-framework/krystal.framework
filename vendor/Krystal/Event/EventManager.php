<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Event;

use Closure;
use RuntimeException;
use InvalidArgumentException;

final class EventManager implements EventManagerInterface
{
	/**
	 * Event listeners with their names
	 * 
	 * @var array
	 */
	private $listeners = array();

	/**
	 * Method overloading to treat undefined calls as triggering events
	 * 
	 * @param string $method
	 * @param array $arguments
	 * @return mixed
	 */
	public function __call($method, array $arguments)
	{
		return $this->trigger($method);
	}

	/**
	 * Attaches a new event
	 * 
	 * @param string $event Event name
	 * @param \Closure $listener
	 * @throws \InvalidArgumentException If either listener isn't callable or event name's isn't a string
	 * @return \Krystal\Event\EventManager
	 */
	public function attach($event, Closure $listener)
	{
		if (!is_callable($listener)) {
			throw new InvalidArgumentException(sprintf(
				'Second argument must be callable not "%s"', gettype($listener)
			));
		}

		if (!is_string($event)) {
			throw new InvalidArgumentException(sprintf(
				'First argument must be string, got "%s"', gettype($event)
			));
		}

		$this->listeners[$event] = $listener;
		return $this;
	}

	/**
	 * Detaches an event
	 * 
	 * @param string $event Event name
	 * @throws \RuntimeException If attempted to detach non-existing event
	 * @return void
	 */
	public function detach($event)
	{
		if ($this->has($event)) {
			unset($this->listeners[$event]);
		} else {
			throw new RuntimeException(sprintf(
				'Cannot detach non-existing event "%s"', $event
			));
		}
	}

	/**
	 * Detaches all events
	 * 
	 * @return void
	 */
	public function detachAll()
	{
		$this->listeners = array();
	}

	/**
	 * Triggers an event
	 * 
	 * @param string $event Event name to be triggered
	 * @throws \RuntimeException If trying to trigger non-existing event
	 * @return mixed
	 */
	public function trigger($event)
	{
		if ($this->has($event)) {
			return call_user_func($this->listeners[$event]);
		} else {
			throw new RuntimeException(sprintf(
				'Cannot trigger non-existing event "%s"', $event
			));
		}
	}

	/**
	 * Checks whether an event is defined
	 * 
	 * @param string $event Event name to be checked for existence
	 * @return boolean
	 */
	public function has($event)
	{
		return isset($this->listeners[$event]);
	}

	/**
	 * Counts amount of defined events
	 * 
	 * @return integer
	 */
	public function countAll()
	{
		return count(array_keys($this->listeners));
	}
}

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

use Krystal\Event\EventManagerInterface;
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
	 * Arguments for listeners
	 * 
	 * @var array
	 */
	private $arguments = array();

	/**
	 * Method overloading : Handle undefined method calls
	 * 
	 * Instead of doing this,
	 * 
	 * $eventManager->trigger('fooMethod', 'someArg');
	 * 
	 * We can do the same thing like this,
	 * 
	 * $eventManager->fooMethod('someArg')
	 * 
	 * @param string $method
	 * @param array $arguments
	 * @return mixed
	 */
	public function __call($method, array $arguments)
	{
		return $this->vtrigger($method, $arguments);
	}

	/**
	 * Attaches a new event
	 * 
	 * @param string $eventName
	 * @param callable $listener
	 * @param array $arguments Arguments to be passed to the listener
	 * @throws \InvalidArgumentException If either listener isn't callable or event name's isn't a string
	 * @return \Krystal\Event\EventManager
	 */
	public function attach($eventName, $listener, array $arguments = array())
	{
		if (!is_callable($listener)) {
			throw new InvalidArgumentException(sprintf(
				'Second argument must be callable not "%s"', gettype($listener)
			));
		}

		if (!is_string($eventName)) {
			throw new InvalidArgumentException(sprintf(
				'First argument must be string, got "%s"', gettype($eventName)
			));
		}

		$this->listeners[$eventName] = $listener;
		$this->arguments[$eventName] = $arguments;

		return $this;
	}

	/**
	 * Detaches an event
	 * 
	 * @param string $eventName
	 * @throws \RuntimeException If attempted to detach non-existing event
	 * @return void
	 */
	public function detach($eventName)
	{
		if ($this->has($eventName)) {
			unset($this->listeners[$eventName], $this->arguments[$eventName]);
		} else {
			throw new RuntimeException(sprintf(
				'Cannot detach non-existing event "%s"', $eventName
			));
		}
	}

	/**
	 * Detaches all attached events with their associated listeners
	 * 
	 * @return void
	 */
	public function detachAll()
	{
		$this->listeners = array();
		$this->events = array();
	}

	/**
	 * Triggers an event with array of provided arguments
	 * 
	 * @param string $eventName
	 * @param array $arguments Additional arguments to be passed to the listener 
	 * @throws \RuntimeException If attempted to trigger undefined event
	 * @return mixed
	 */
	public function vtrigger($eventName, $arguments = array())
	{
		if ($this->has($eventName)) {
			$arguments = array_merge($this->arguments[$eventName], $arguments);
			return call_user_func_array($this->listeners[$eventName], $arguments);

		} else {
			throw new RuntimeException(sprintf(
				'Cannot trigger non-existing event "%s"', $eventName
			));
		}
	}

	/**
	 * Triggers an event
	 * 
	 * @param string $eventName
	 * @param mixed ...
	 * @return mixed
	 */
	public function trigger()
	{
		$arguments = func_get_args();
		$eventName = array_shift($arguments);

		return $this->vtrigger($eventName, $arguments);
	}

	/**
	 * Checks whether event has been defined before
	 * 
	 * @param string $eventName
	 * @return boolean
	 */
	public function has($eventName)
	{
		if (!isset($this->listeners[$eventName]) || !isset($this->arguments[$eventName])) {
			return false;
		}

		return true;
	}

	/**
	 * Counts amount of defined events
	 * 
	 * @return integer
	 */
	public function countAll()
	{
		return count(array_keys($this->events));
	}
}

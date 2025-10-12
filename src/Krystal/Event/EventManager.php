<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Event;

use Closure;
use RuntimeException;
use InvalidArgumentException;

/**
 * A lightweight implementation of the Observer Pattern, acting as a centralized event bus.
 * Allows attaching, detaching, and triggering named events (listeners) to decouple
 * components through event-based communication.
 */
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
     * Registers a new event listener under a given event name.
     * 
     * The listener must be a valid callable (Closure). When the event is triggered,
     * the assigned listener will be executed. If the same event name already exists,
     * the previous listener will be overwritten.
     *
     * @param string $event The name of the event to register.
     * @param \Closure $listener The callback to be executed when the event is triggered.
     * @throws \InvalidArgumentException If the event name is not a string or the listener is not callable.
     * @return \Krystal\Event\EventManager Returns the current instance for method chaining.
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
     * Registers multiple event listeners at once.
     * 
     * Accepts an associative array where each key represents an event name
     * and each value is a corresponding listener (Closure). Existing listeners
     * with the same event names will be overwritten.
     *
     * @param array $collection An associative array of event names mapped to listeners.
     * @return \Krystal\Event\EventManager Returns the current instance for method chaining.
     */
    public function attachMany(array $collection)
    {
        foreach ($collection as $event => $listener) {
            $this->attach($event, $listener);
        }

        return $this;
    }

    /**
     * Removes a previously registered event listener by its event name.
     * 
     * If the specified event does not exist, a RuntimeException will be thrown.
     * This method is useful for dynamically disabling specific event hooks at runtime.
     *
     * @param string $event The name of the event to detach.
     * @throws \RuntimeException If attempting to detach a non-existing event.
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
     * Removes all registered event listeners.
     * 
     * Clears the entire event registry, effectively disabling all attached events.
     * Useful for resetting the event manager to an empty state.
     * 
     * @return void
     */
    public function detachAll()
    {
        $this->listeners = array();
    }

    /**
     * Triggers a registered event by its name.
     * 
     * Executes the listener (Closure) associated with the specified event.
     * If the event does not exist, a RuntimeException will be thrown.
     * The return value depends on the listener’s callback result.
     *
     * @param string $event The name of the event to trigger.
     * @throws \RuntimeException If attempting to trigger a non-existing event.
     * @return mixed Returns the result of the executed listener callback.
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
     * Determines whether an event with the given name is registered.
     * Returns true if the specified event exists in the registry, false otherwise.
     *
     * @param string $event The name of the event to check.
     * @return bool True if the event is registered, false otherwise.
     */
    public function has($event)
    {
        return isset($this->listeners[$event]);
    }

    /**
     * Determines whether all specified events are registered.
     * 
     * Accepts an array of event names and checks if each one exists
     * in the event registry. Returns true only if all events are found.
     *
     * @param array $events A list of event names to verify.
     * @return bool True if all events exist, false otherwise.
     */
    public function hasMany(array $events)
    {
        foreach ($events as $event) {
            if (!$this->has($event)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Returns the total number of registered events.
     * Counts how many event listeners are currently defined in the event registry.
     * 
     * @return int The total number of registered events.
     */
    public function countAll()
    {
        return count(array_keys($this->listeners));
    }
}

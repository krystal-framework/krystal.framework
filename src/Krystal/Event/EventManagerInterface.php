<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Event;

use Closure;

interface EventManagerInterface
{
    /**
     * Attaches a new event
     * 
     * @param string $event Event name
     * @param \Closure $listener
     * @throws \InvalidArgumentException If either listener isn't callable or event name's isn't a string
     * @return \Krystal\Event\EventManager
     */
    public function attach($event, Closure $listener);

    /**
     * Attaches several events at once
     * 
     * @param array $collection
     * @return \Krystal\Event\EventManager
     */
    public function attachMany(array $collection);

    /**
     * Detaches an event
     * 
     * @param string $event Event name
     * @throws \RuntimeException If attempted to detach non-existing event
     * @return void
     */
    public function detach($event);

    /**
     * Detaches all events
     * 
     * @return void
     */
    public function detachAll();

    /**
     * Triggers an event
     * 
     * @param string $event Event name to be triggered
     * @throws \RuntimeException If trying to trigger non-existing event
     * @return mixed
     */
    public function trigger($event);

    /**
     * Checks whether an event is defined
     * 
     * @param string $event Event name to be checked for existence
     * @return boolean
     */
    public function has($event);

    /**
     * Checks whether event names are registered
     * 
     * @param array $events
     * @return boolean
     */
    public function hasMany(array $events);

    /**
     * Counts amount of defined events
     * 
     * @return integer
     */
    public function countAll();
}

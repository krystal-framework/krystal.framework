<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validate;

abstract class AbstractConstraint
{
	/**
     * Whether constraint should be the chain on failure
     * 
     * @var boolean
     */
    protected $breakable;

    /**
     * @var boolean
	 */
    protected $required;

    /**
     * Default error message
     * 
     * @var string
     */
    protected $messages = array();

    /**
     * Default charset
     * 
     * @var string
     */
    protected $charset = 'UTF-8';

    /**
     * Checks whether we have at least one error message
     * 
     * @return boolean
     */
    public function hasErrors()
    {
        return !empty($this->messages);
    }

    /**
     * Sets error message
     * 
     * @param string $message
     * @return void
     */
    public function addMessage($message)
    {
        $this->messages[] = $message;
    }

    /**
     * Notifies about constraint violation
     * 
     * @throws RuntimeException
     * @return void
     */
    public function violate($message)
    {
        if (!empty($this->messages)){
            $message = $this->messages[0];
        } else {
            $this->setMessage($message);
        }
    }

    /**
     * Erase all previous messages and set a new one
     * 
     * @param string $message
     * @return void
     */
    public function setMessage($message)
    {
        $this->messages = array();
        $this->addMessage($message);
    }

    /**
     * Returns error message
     * 
     * @return string
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Defines whether a constraint should be required or not
     * 
     * @param boolean $required
     * @return void
     */
    public function setRequired($required)
    {
        $this->required = $required;
    }

    /**
     * Returns true if a constraint instantiation should be done
     * 
     * @return boolean
     */
    public function getRequired()
    {
        return $this->required;
    }

    /**
     * Defines whether constraint should be breakable
     * 
     * @param boolean $breakable
     * @return void
     */
    public function setBreakable($breakable)
    {
        $this->breakable = $breakable;
    }

    /**
     * Returns true if a constraint should break the chain
     * 
     * @return boolean
     */
    public function getBreakable()
    {
        return $this->breakable;
    }
}

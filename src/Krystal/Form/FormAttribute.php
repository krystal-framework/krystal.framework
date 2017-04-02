<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Form;

use Krystal\Session\SessionBagInterface;

final class FormAttribute implements FormAttributeInterface
{
    const PARAM_STORAGE_KEY = 'attributes';

    /**
     * Session bag service
     * 
     * @var \Krystal\Session\SessionBagInterface
     */
    private $sessionBag;

    /**
     * A collection of new attributes
     * 
     * @var array
     */
    private $attributes = array();

    /**
     * State initialization
     * 
     * @param \Krystal\Session\SessionBagInterface $sessionBag
     * @return void
     */
    public function __construct(SessionBagInterface $sessionBag)
    {
        $this->sessionBag = $sessionBag;

        if (!$this->sessionBag->has(self::PARAM_STORAGE_KEY)) {
            $this->sessionBag->set(self::PARAM_STORAGE_KEY, array());
        }
    }

    /**
     * Returns a collection of old attributes
     * 
     * @return array
     */
    private function getOldAttributes()
    {
        return $this->sessionBag->get(self::PARAM_STORAGE_KEY);
    }

    /**
     * Determines whether old attribute has been set
     * 
     * @param string $name Attribute name
     * @return boolean
     */
    private function hasOldAttribute($name)
    {
        $collection = $this->getOldAttributes();
        return array_key_exists($name, $collection);
    }

    /**
     * Returns old attribute value
     * 
     * @param string $name Attribute name
     * @return mixed
     */
    private function getOldAttribute($name)
    {
        if ($this->hasOldAttribute($name)) {
            $collection = $this->getOldAttributes();
            return $collection[$name];
        } else {
            // Error
        }
    }

    /**
     * Appends an attribute to collection
     * 
     * @param string $name
     * @param mixed $value
     * @return void
     */
    private function appendOldAttributes(array $attributes)
    {
        // Get a current collection
        $collection = $this->sessionBag->get(self::PARAM_STORAGE_KEY);

        // Merge new attributes with collection
        $collection = array_merge($collection, $attributes);

        // Override old collection with a new one
        $this->sessionBag->set(self::PARAM_STORAGE_KEY, $collection);
    }

    /**
     * Set old form attribute
     * 
     * @param string $name
     * @parm mixed $value
     * @return \Krystal\Form\FormAttribute
     */
    public function setOldAttribute($name, $value)
    {
        $this->appendOldAttributes(array($name => $value));
        return $this;
    }

    /**
     * Set a collection of old attributes
     * 
     * @param array $attributes
     * @return \Krystal\Form\FormAttribute
     */
    public function setOldAttributes(array $attributes)
    {
        $this->appendOldAttributes($attributes);
        return $this;
    }

    /**
     * Set a collection of new attributes
     * 
     * @param array $attributes
     * @return \Krystal\Form\FormAttribute
     */
    public function setNewAttributes(array $attributes)
    {
        $this->attributes = $attributes;
        return $this;
    }

    /**
     * Checks whether new attribute exists
     * 
     * @param string $name Attribute name
     * @return boolean
     */
    public function hasNewAttribute($name)
    {
        return array_key_exists($name, $this->attributes);
    }

    /**
     * Returns a value of new attribute
     * 
     * @param string $name
     * @return mixed
     */
    public function getNewAttribute($name)
    {
        if ($this->hasNewAttribute($name)) {
            return $this->attributes[$name];
        } else {
            // Error
        }
    }

    /**
     * Determines whether attribute has been changed or not
     * 
     * @param string $name Attribute name
     * @return boolean
     */
    public function hasChanged($name)
    {
        if ($this->hasOldAttribute($name) && $this->hasNewAttribute($name)) {
            return $this->getOldAttribute($name) != $this->getNewAttribute($name);
        } else {
            // Unknown attribute
        }
    }

    /**
     * Returns a collection of changed attributes
     * 
     * @return array
     */
    public function getChangedAttributes()
    {
        $output = array();

        foreach ($this->attributes as $name => $value) {
            if ($this->hasChanged($name)) {
                $output[$name] = $value;
            }
        }

        return $output;
    }

    /**
     * Returns a collection of unchanged attributes
     * 
     * @return array
     */
    public function getUnchangedAttributes()
    {
        $output = array();

        foreach ($this->attributes as $name => $value) {
            if (!$this->hasChanged($name)) {
                $output[$name] = $value;
            }
        }

        return $output;
    }
}

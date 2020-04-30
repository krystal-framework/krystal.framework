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

interface FormAttributeInterface
{
    /**
     * Set old form attribute
     * 
     * @param string $name
     * @parm mixed $value
     * @return \Krystal\Form\FormAttribute
     */
    public function setOldAttribute($name, $value);

    /**
     * Set a collection of old attributes
     * 
     * @param array $attributes
     * @return \Krystal\Form\FormAttribute
     */
    public function setOldAttributes(array $attributes);

    /**
     * Append new attribute
     * 
     * @param string $key
     * @param string $value
     * @return \Krystal\Form\FormAttribute
     */
    public function setNewAttribute($key, $value);

    /**
     * Set a collection of new attributes
     * 
     * @param array $attributes
     * @return \Krystal\Form\FormAttribute
     */
    public function setNewAttributes(array $attributes);

    /**
     * Checks whether new attribute exists
     * 
     * @param string $name Attribute name
     * @return boolean
     */
    public function hasNewAttribute($name);

    /**
     * Returns a value of new attribute
     * 
     * @param string $name
     * @return mixed
     */
    public function getNewAttribute($name);

    /**
     * Determines whether attribute has been changed or not
     * 
     * @param string $name Attribute name
     * @oaram string $value New value
     * @return boolean
     */
    public function hasChanged($name, $value = null);

    /**
     * Returns a collection of changed attributes
     * 
     * @return array
     */
    public function getChangedAttributes();

    /**
     * Returns a collection of unchanged attributes
     * 
     * @return array
     */
    public function getUnchangedAttributes();
}

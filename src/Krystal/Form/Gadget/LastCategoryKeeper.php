<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Form\Gadget;

use Krystal\Http\PersistentStorageInterface;

final class LastCategoryKeeper extends AbstractGadget
{
    /**
     * Whether category id should be flashed or not (i.e automatically removed after returning)
     * 
     * @var boolean
     */
    private $flashed;

    /**
     * State initialization
     * 
     * @param \Krystal\Http\PersistentStorageInterface $storage
     * @param string $key Key name to be allocated
     * @param boolean $flashed
     * @return void
     */
    public function __construct(PersistentStorageInterface $storage, $key, $flashed = false)
    {
        $this->flashed = $flashed;

        parent::__construct($storage, $key, false, array());
    }

    /**
     * Checks whether category id has been persisted
     * 
     * @return boolean
     */
    public function hasLastCategoryId()
    {
        return $this->hasData();
    }

    /**
     * Persists last category id
     * 
     * @param string $id
     * @return boolean
     */
    public function persistLastCategoryId($id)
    {
        $this->setData($id);
        return $this;
    }

    /**
     * Returns last category id
     * 
     * @return string
     */
    public function getLastCategoryId()
    {
        $value = $this->getData();

        if ($this->flashed === true) {
            // Clear data
            $this->clearData();
        }

        return $value;
    }
}

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
    const PARAM_LAST_CATEGORY_ID = 'last_category_id';

    /**
     * State initialization
     * 
     * @param \Krystal\Http\PersistentStorageInterface $storage
     * @return void
     */
    public function __construct(PersistentStorageInterface $storage)
    {
        parent::__construct($storage, self::PARAM_LAST_CATEGORY_ID, false, array());
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
        // Clear data
        $this->clearData();

        return $value;
    }
}

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Form\Gadget;

interface LastCategoryKeeperInterface
{
    /**
     * Checks whether category id has been persisted
     * 
     * @return boolean
     */
    public function hasLastCategoryId();

    /**
     * Persists last category id
     * 
     * @param string $id
     * @return boolean
     */
    public function persistLastCategoryId($id);

    /**
     * Returns last category id
     * 
     * @return string
     */
    public function getLastCategoryId();
}

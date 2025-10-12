<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Form\Gadget;

class DataSorter extends AbstractGadget implements DataSorterInterface
{
    /**
     * Returns sorting options
     * 
     * @return array
     */
    public function getSortingOptions()
    {
        return $this->values;
    }

    /**
     * Returns current sort option
     * 
     * @return string
     */
    public function getSortOption()
    {
        return $this->getData();
    }

    /**
     * Stores sorting option
     * 
     * @param string $sort
     * @return boolean
     */
    public function setSortOption($sort)
    {
        return $this->setData($sort);
    }
}

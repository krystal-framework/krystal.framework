<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Db\Filter;

interface FilterInvokerInterface
{
    /**
     * Invokes a filter
     * 
     * @param \Krystal\Db\Filter\FilterableServiceInterface $service
     * @param integer $perPageCount Amount of items to be display per page
     * @param array $parameters Custom user-defined parameters
     * @return array
     */
    public function invoke(FilterableServiceInterface $service, $perPageCount, array $parameters = array());
}

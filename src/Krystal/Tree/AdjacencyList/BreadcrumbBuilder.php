<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Tree\AdjacencyList;

use Closure;

final class BreadcrumbBuilder implements BreadcrumbBuilderInterface
{
    /**
     * Raw data
     * 
     * @var array
     */
    private $raw;

    /**
     * Current parent or child id
     * 
     * @var string
     */
    private $id;

    /**
     * State initialization
     * 
     * @param array $raw Raw data
     * @param string $id
     * @return void
     */
    public function __construct(array $raw, $id)
    {
        $this->raw = $raw;
        $this->id = $id;
    }

    /**
     * Returns parsed data for breadcrumbs
     * 
     * @return array
     */
    private function getData()
	{
        $treeBuilder = new TreeBuilder($this->raw);
        return $treeBuilder->findAll($this->id);
    }

	/**
     * Makes breadcrumbs
     * 
     * @param \Closure $visitor
     * @return array
	 */
    public function makeAll(Closure $visitor)
    {
        $data = $this->getData();
        $result = array();

        if (!empty($data)) {
            foreach ($data as $breadcrumb) {
                $result[] = $visitor($breadcrumb);
            }

            $result = array_reverse($result);
        }

        return $result;
    }
}

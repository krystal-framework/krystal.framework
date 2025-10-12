<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Tree\AdjacencyList\Render;

abstract class AbstractRenderer
{
    /**
     * Target options
     * 
     * @var array
     */
    protected $options = array();

    /**
     * State initialization
     * 
     * @param array $options
     * @return void
     */
    public function __construct(array $options = array())
    {
        $this->options = $options;
    }

    /**
     * Returns an option
     * 
     * @param string $key
     * @return mixed
     */
    protected function getOption($key)
	{
        return $this->options[$key];
    }

    /**
     * Checks whether we have an option key
     * 
     * @param string $key
     * @return boolean
     */
    protected function hasOption($key)
    {
        return array_key_exists($key, $this->options);
    }

    /**
     * Determines whether we have some class
     * 
     * @param string $class
     * @return boolean
     */
    protected function hasClass($class)
    {
        if (!$this->hasOption('class')) {
            return false;
        } else {
            return isset($this->options['class'][$class]);
        }
    }

    /**
     * Returns class value
     * 
     * @param string $class
     * @return string
     */
    protected function getClass($class)
    {
        return $this->options['class'][$class];
    }

    /**
     * Return child ids associated with target parent id
     * 
     * @param string|integer $id
     * @return array
     */
    protected function getChildrenByParent($id)
    {
        //@todo: write this
    }

    /**
     * Count amount of children associated with target id
     * 
     * @param string|integer $id
     * @return integer
     */
    protected function getChildrenCount($id)
	{
        return count($this->getChildrenByParent($id));
	}

    /**
     * Checks whether target id has at least one child
     * 
     * @param string $id
     * @param array $relationships
     * @return boolean
     */
    protected function hasChildren($id, array $relationships)
    {
        // Get only ids that have at least one child
        $parents = array_keys($relationships);
        return in_array($id, $parents);
    }

    /**
     * Determines whether given id has a parent
     * 
     * @param string|integer $id
     * @param array $relationships
     * @return boolean
     */
    protected function hasParent($id, array $relationships)
    {
        foreach ($relationships as $parentId => $children) {
            // 0 is reserved, therefore must be ignored
            if ($parentId == 0) {
                continue;
            }

            if (in_array($id, $children)) {
                return true;
            }
        }

        // By default
        return false;
    }

    /**
     * Renders adjacency list as a tree
     * 
     * @param array $data
     * @param mixed $active Active value to be matched against a list
     * @param string $parentId This should passed on recursive call only
     * @return string
     */
    abstract public function render(array $data, $active = false, $parentId = 0);
}

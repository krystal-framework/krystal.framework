<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\Model;

abstract class AbstractService
{
    /**
     * Calls hydration method
     * 
     * @param string $target
     * @param array $args
     * @return mixed
     */
    private function callHydrator($target, array $args)
    {
        $callback = array($this, 'toEntity');
        return call_user_func_array($callback, array_merge(array($target), $args));
    }

    /**
     * Converts an array to object
     * 
     * @param array $array
     * @return array
     */
    final protected function prepareResults()
    {
        $args = func_get_args();
        $array = array_shift($args);

        if (!empty($array)) {
            $result = array();
            foreach ($array as $target) {
                array_push($result, $this->callHydrator($target, $args));
            }

            return $result;
        } else {
            return array();
        }
    }

    /**
     * Prepares a result
     * 
     * @return \Krystal\Stdlib\VirtualEntity|boolean
     */
    final protected function prepareResult()
    {
        $args = func_get_args();
        $result = array_shift($args);

        if ($result && $result !== false) {
            return $this->callHydrator($result, $args);
        } else {
            return false;
        }
    }
}

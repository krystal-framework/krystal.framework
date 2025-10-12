<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Db\Sql;

use Krystal\InstanceManager\InstanceBuilder;
use ReflectionClass;
use PDO;

/**
 * Acts as a real PDO instance, but connects on demand
 */
final class LazyPDO
{
    /**
     * PDO instance argument
     * 
     * @var array
     */
    private $args = array();

    /**
     * State initialization
     * 
     * @return void
     */
    public function __construct()
    {
        $this->args = func_get_args();
    }

    /**
     * Lazily returns PDO instance
     * 
     * @return \PDO
     */
    private function getPdo()
    {
        static $pdo = null;

        if (is_null($pdo)) {
            $builder = new InstanceBuilder();
            $pdo = $builder->build('PDO', $this->args);
        }

        return $pdo;
    }

    /**
     * Calls a method on PDO
     * 
     * @param string $method Target method
     * @param array $args Array of arguments to be passed into the method
     * @return array
     */
    public function __call($method, array $args)
    {
        $pdo = $this->getPdo();

        if (method_exists($pdo, $method)) {
            return call_user_func_array(array($pdo, $method), $args);

        } else {
            trigger_error(sprintf('Attempted to call non-existing method on PDO %s', $method));
        }
    }
}

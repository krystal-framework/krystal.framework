<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Db\Sql;

use Krystal\InstanceManager\InstanceBuilder;
use BadMethodCallException;
use PDO;

/**
 * Acts as a real PDO instance, but connects on demand
 * @mixin \PDO
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
     * Current PDO instance
     * 
     * @var \PDO
     */
    private $pdo = null;

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
        if (is_null($this->pdo)) {
            $builder = new InstanceBuilder();
            $this->pdo = $builder->build('PDO', $this->args);
        }

        return $this->pdo;
    }

    /**
     * Calls a method on PDO
     * 
     * @param string $method Target method
     * @param array $args Array of arguments to be passed into the method
     * @throws \BadMethodCallException When calling undefined method
     * @return mixed
     */
    public function __call($method, array $args)
    {
        $pdo = $this->getPdo();

        if (is_callable(array($pdo, $method))) {
            return call_user_func_array(array($pdo, $method), $args);
        } else {
            throw new BadMethodCallException(sprintf('Attempted to call non-existing method on PDO: %s', $method));
        }
    }
}

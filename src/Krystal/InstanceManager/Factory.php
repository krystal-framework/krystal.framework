<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\InstanceManager;

class Factory
{
    /**
     * Target namespace
     * 
     * @var string
     */
    protected $namespace;

    /**
     * Defines a namespace
     * 
     * @param string $namespace
     * @return void
     */
    final public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * Returns a namespace
     * 
     * @return string
     */
    final public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * Builds a classname according to defined pseudo-namespace
     * 
     * @param string $filename PSR-0 compliant name
     * @return string
     */
    final protected function buildClassNameByFileName($filename)
    {
        $className = sprintf('%s/%s', $this->getNamespace(), $filename);
        // Normalize it
        $className = str_replace(array('//', '/', '\\'), '\\', $className);

        return $className;
    }

    /**
     * Builds an instance
     * Heavily relies on PSR-0 autoloader
     * 
     * @param string $filename (Without extension and base path)
     * @param mixed $arguments [...]
     * @throws \RuntimeException if cannot load a class
     * @return object
     */
    final public function build()
    {
        $arguments = func_get_args();
        $filename = array_shift($arguments);

        $className = $this->buildClassNameByFileName($filename);

        $ib = new InstanceBuilder();
        return $ib->build($className, $arguments);
    }
}

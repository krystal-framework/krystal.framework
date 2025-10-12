<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Config\File;

use Krystal\Serializer\NativeSerializer as Serializer;
use Krystal\Config\ConfigModuleServiceInterface;
use LogicException;
use RuntimeException;
use InvalidArgumentException;

final class FileConfigService implements ConfigModuleServiceInterface
{
    /**
     * Configuration file path
     * 
     * @var string
     */
    private $path;

    /**
     * Array hash. The hash is fast and efficient way to 
     * determine the changes within an array. 
     * 
     * @var string
     */
    private $hash;

    /**
     * Indicates whether configuration file has been loaded
     * 
     * @var boolean
     */
    private $loaded = false;

    /**
     * Serialization service
     * 
     * @var \Krystal\Serializer\AbstractSerializer
     */
    private $serializer;

    /**
     * File type handler
     * 
     * @var \Krystal\Config\File\FileTypeInterface
     */
    private $fileType;

    /**
     * Current configuration representation
     * 
     * @var array
     */
    private $config = array();

    /**
     * Whether to terminate the script when invalid file path supplied
     * or try to create a new file
     * 
     * @var boolean
     */
    private $autoCreate;

    /**
     * State initialization
     * 
     * @param string $path Path to configuration file
     * @param \Krystal\Config\File\FileTypeInterface $fileType
     * @param boolean $autoCreate Whether to create a file automatically
     * @return void
     */
    public function __construct($path, FileTypeInterface $fileType, $autoCreate = true)
    {
        $this->path = $path;
        $this->fileType = $fileType;
        $this->serializer = new Serializer();
        $this->autoCreate = $autoCreate;
    }

    /**
     * Returns current configuration array
     * 
     * @return array
     */
    public function getAll()
    {
        $this->load();
        return $this->config;
    }

    /**
     * Saves the content to the disk
     * 
     * @param array $array
     * @return boolean Depending on success
     */
    public function save()
    {
        $this->load();
        // Do the work in case we have only changed hash
        if ($this->isChanged()) {
            @chmod($this->path, 0777);
            return file_put_contents($this->path, $this->fileType->render($this->config));
        } else {
            return true;
        }
    }

    /**
     * Writes configuration pair
     * 
     * @param string $key
     * @param string $value
     * @return boolean
     */
    public function store($key, $value)
    {
        $this->load();

        $this->config[$key] = $value;
        return true;
    }

    /**
     * Sets a collection
     * 
     * @param array $pair
     * @return boolean
     */
    public function storeMany(array $pair)
    {
        foreach ($pair as $key => $value) {
            $this->set($key, $value);
        }

        return true;
    }

    /**
     * Checks whether configuration key exists
     * 
     * @param string $key
     * @return boolean
     */
    public function has($key)
    {
        $this->load();
        return array_key_exists($key, $this->config);
    }

    /**
     * Checks whether many configuration keys exists at once
     * 
     * @param array $keys
     * @return boolean
     */
    public function hasMany(array $keys)
    {
        foreach ($keys as $key) {
            if (!$this->has($key)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Reads a value by a given key
     * 
     * @param string $key
     * @param mixed $default Default value to be returned in $key doesn't exist
     * @return mixed
     */
    public function get($key, $default = false)
    {
        $this->load();

        if ($this->has($key)) {
            return $this->config[$key];
        } else {
            return $default;
        }
    }

    /**
     * Deletes configuration value by its associated key
     * 
     * @param string $key
     * @throws \RuntimeException if attempted to remove by non-existing key
     * @return boolean
     */
    public function remove($key)
    {
        $this->load();

        if ($this->exists($key)) {
            unset($this->config[$key]);
            return true;
        } else {
            throw new RuntimeException(sprintf('Attempted to read non-existing key "%s"', $key));
        }
    }

    /**
     * Truncates configuration array
     * 
     * @return void
     */
    public function removeAll()
    {
        $this->load();

        $this->config = array();
        return true;
    }

    /**
     * Loads configuration into memory on demand
     * 
     * @throws \LogicException If included file doesn't return an array
     * @return boolean Depending on success
     */
    private function load()
    {
        if ($this->loaded === false) {
            if ($this->autoCreate === true) {
                if (!is_file($this->path)) {
                    $this->touch();
                }
            }

            $array = $this->fileType->fetch($this->path);

            if (is_array($array)) {
                // Keep initial array hash
                $this->hash = $this->serializer->buildHash($array);
                $this->config = $array;
                $this->loaded = true;

                return true;
            } else {
                throw new LogicException(sprintf(
                    'Required file should return an array and only, not "%s"', gettype($array)
                ));
            }
        }

        return true;
    }

    /**
     * Creates new empty file
     * 
     * @return boolean Depending on success
     */
    private function touch()
    {
        return $this->save();
    }

    /**
     * Check whether initial and final arrays are different
     * 
     * @return boolean TRUE if changed, FALSE if not
     */
    private function isChanged()
    {
        return $this->hash !== $this->serializer->buildHash($this->config);
    }
}

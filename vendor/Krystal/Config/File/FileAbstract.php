<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Config\File;

use Krystal\Serializer\NativeSerializer as Serializer;

abstract class FileAbstract
{
	/**
	 * File path
	 * 
	 * @var string
	 */
	protected $path;

	/**
	 * Array hash. The hash is fast and efficient way to 
	 * determine the changes within an array. 
	 * 
	 * We'll just compare initial hash against the latest one.
	 * If hashes do differ, then arrays are different.
	 * 
	 * @var string
	 */
	protected $hash;

	/**
	 * Indicates whether config file has been loaded
	 * 
	 * @var boolean
	 */
	protected $loaded = false;

	/**
	 * Data serializer
	 * 
	 * @var object
	 */
	protected $serializer;

	/**
	 * Config representation
	 * 
	 * @var array
	 */
	protected $config = array();

	/**
	 * Whether to terminate the script when invalid file supplied
	 * 
	 * When its FALSE, then script would stop on failure
	 * When its TRUE, then script would not stop and create a new file instead
	 * 
	 * @const boolean
	 */
	const AUTO_CREATE = true;

	/**
	 * State initialization
	 * 
	 * @param string $path
	 * @return void
	 */
	public function __construct($path)
	{
		$this->path = $path;
		// @TODO : get rid of tight-coupling
		$this->serializer = new Serializer();
	}

	/**
	 * Merges one or more
	 * 
	 * @param array $config
	 * @throws InvalidArgumentException 
	 * @return void
	 */
	public function merge()
	{
		foreach (func_get_args() as $arg) {
			if (is_array($arg)) {
				$this->config = array_merge($this->config, $arg);
			} else {
				throw new InvalidArgumentException(sprintf(
					'An argument must be an array, received "%s"', gettype($arg)
				));
			}
		}
	}

	/**
	 * Each descendant must represent its nature
	 * 
	 * @return string
	 */
	abstract protected function render();

	/**
	 * Fetches as PHP array representation
	 * 
	 * @return string 
	 */
	abstract protected function fetch();

	/**
	 * Loads configuration into memory
	 * 
	 * @return boolean Depending on success
	 */
	public function load()
	{
		if (self::AUTO_CREATE) {
			if (!is_file($this->path)) {
				$this->touch();
			}
		}

		$array = $this->fetch();

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

	/**
	 * Creates new empty file
	 * 
	 * @return boolean Depending on success
	 */
	protected function touch()
	{
		return $this->save();
	}

	/**
	 * Check whether initial and final arrays are different
	 * 
	 * @return boolean TRUE if changed, FALSE if not
	 */
	protected function isChanged()
	{
		return $this->hash !== $this->serializer->buildHash($this->config);
	}

	/**
	 * Returns native config array
	 * 
	 * @return array
	 */
	public function getConfig()
	{
		return $this->config;
	}

	/**
	 * Define config
	 * 
	 * @param array $config
	 * @return void
	 */
	public function setConfig(array $config)
	{
		$this->config = $config;
	}

	/**
	 * Saves the content to the disk
	 * 
	 * @param array $array
	 * @return boolean Depending on success
	 */
	public function save()
	{
		// Do the work in case we have only changed hash
		if ($this->isChanged()) {
			
			@chmod($this->path, 0777);
			
			return file_put_contents($this->path, $this->render());
		} else {
			return true;
		}
	}

	/**
	 * Writes configuration pair
	 * 
	 * @param array	$data The data we need to write
	 * @return boolean Always TRUE
	 */
	public function set($key, $value)
	{
		$this->config[$key] = $value;
		return true;
	}

	/**
	 * Sets a collection
	 * 
	 * @param array $pair
	 * @return boolean
	 */
	public function setPair(array $pair)
	{
		foreach ($pair as $key => $value) {
			$this->set($key, $value);
		}
		
		return true;
	}

	/**
	 * Determine whether keys exist
	 * 
	 * @param string $key
	 * @return boolean
	 */
	public function exists()
	{
		foreach (func_get_args() as $argument) {
			if (!array_key_exists($argument, $this->config)) {
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
	public function get($key, $default = null)
	{
		if ($this->exists($key)) {
			return $this->config[$key];
		} else {
			return $default;
		}
	}

	/**
	 * Truncates configuration array
	 * 
	 * @return void
	 */
	public function flush()
	{
		$this->config = array();
	}

	/**
	 * Deletes a key
	 * 
	 * @param string $key
	 * @throws RuntimeException
	 * @return boolean
	 */
	public function delete($key)
	{
		if ($this->exists($key)) {
			unset($this->config[$key]);
		} else {
			throw new RuntimeException(sprintf('Attempted to read non-existing key "%s"', $key));
		}
	}
}

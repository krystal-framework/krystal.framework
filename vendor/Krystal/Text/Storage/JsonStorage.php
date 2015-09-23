<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Text\Storage;

use Krystal\Serializer\JsonSerializer;
use Krystal\Http\PersistentStorageInterface;

/**
 * This tool can be used to read/write multi-dimensional arrays storing either in Session or Cookies
 */
final class JsonStorage implements StorageInterface
{
	/**
	 * Data serializer
	 * 
	 * @var \Krystal\Serializer\JsonSerializer
	 */
	private $serializer;

	/**
	 * Storage adapter to store JSON-serialized data
	 * 
	 * @var \Krystal\Http\PersistentStorageInterface
	 */
	private $storageAdapter;

	/**
	 * Key's name that represents serialized data
	 * 
	 * @var string
	 */
	private $key;

	/**
	 * Time to live in seconds
	 * 
	 * @var integer
	 */
	private $ttl;

	/**
	 * State initialization
	 * 
	 * @param \Krystal\Http\PersistentStorageInterface $storageAdapter Either session or cookie
	 * @param string $key Storage key
	 * @param integer $ttl Time to live in seconds
	 * @return void
	 */
	public function __construct(PersistentStorageInterface $storageAdapter, $key, $ttl)
	{
		$this->key = $key;
		$this->storageAdapter = $storageAdapter;
		$this->ttl = $ttl;
		$this->serializer = new JsonSerializer();
	}

	/**
	 * Loads data from a storage
	 * 
	 * @return array Returns loaded data
	 */
	public function load()
	{
		// Initial state, is always empty array
		$data = array();

		// Alter $data in case it exists and its valid
		if ($this->storageAdapter->has($this->key)) {
			$target = $this->serializer->unserialize($this->storageAdapter->get($this->key));

			// If $target is null, then data either is damaged or doesn't exist, otherwise it's okay
			if ($target !== null) {
				$data = $target;
			}
		}

		return $data;
	}

	/**
	 * Saves data into a storage
	 * 
	 * @param array $data Data to be saved
	 * @return void
	 */
	public function save(array $data)
	{
		// This is what we're going to store
		$seriaziledData = $this->serializer->serialize($data);
		$this->storageAdapter->set($this->key, $seriaziledData, $this->ttl);

		return true;
	}

	/**
	 * Clears data from a storage
	 * 
	 * @return void
	 */
	public function clear()
	{
		if ($this->storageAdapter->has($this->key)) {
			$this->storageAdapter->remove($this->key);
		}
	}
}

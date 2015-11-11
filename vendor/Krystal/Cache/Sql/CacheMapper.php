<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Cache\Sql;

use PDO;
use Krystal\Serializer\AbstractSerializer;

/* An abstraction over table's common operations */
final class CacheMapper implements CacheMapperInterface
{
    /**
     * Prepared PDO instance
     * 
     * @var \PDO
     */
    private $pdo;

    /**
     * Data serializer
     * 
     * @var \Krystal\Serializer\AbstractSerializer
     */
    private $serializer;

    /**
     * Table name
     * 
     * @var string
     */
    private $table;

    /**
     * State initialization
     * 
     * @param \Krystal\Serializer\AbstractSerializer $serializer
     * @param \PDO $pdo
     * @param string $table
     * @return void
     */
    public function __construct(AbstractSerializer $serializer, $pdo, $table)
    {
        $this->serializer = $serializer;
        $this->pdo = $pdo;
        $this->table = $table;
    }

    /**
     * Flushes the cache
     * 
     * @return boolean
     */
    public function flush()
    {
        $query = sprintf('TRUNCATE `%s`', $this->table);
        return $this->pdo->exec($query) !== false;
    }

    /**
     * Fetches cache data
     * 
     * @return array
     */
    public function fetchAll()
    {
        $query = sprintf('SELECT * FROM `%s`', $this->table);
        $result = array();

        foreach ($this->pdo->query($query) as $key => $value) {
            $result[$key] = $value;
        }

        return $this->parse($result);
    }

    /**
     * Alters a column
     * 
     * @param string $key
     * @param string $operator
     * @param integer $step
     * @return boolean
     */
    private function alter($key, $operator, $step)
    {
        $query = sprintf('UPDATE `%s` SET `value` = value %s %s WHERE `key` = :key', $this->table, $operator, $step);

        $stmt = $this->pdo->prepare($query);
        return $stmt->execute(array(
            ':key' => $key
        ));
    }

    /**
     * Increments a field
     * 
     * @param string $key
     * @param integer $step
     * @return boolean
     */
    public function increment($key, $step)
    {
        return $this->alter($key, '+', $step);
    }

    /**
     * Decrements a field
     * 
     * @param string $key
     * @param integer $step
     * @return boolean
     */
    public function decrement($key, $step)
    {
        return $this->alter($key, '-', $step);
    }

    /**
     * Inserts new cache's record
     * 
     * @param string $key
     * @param mixed
     * @param integer $ttl Time to live in seconds
     * @param integer $time Current timestamp
     * @return boolean
     */
    public function insert($key, $value, $ttl, $time)
    {
        if ($this->serializer->isSerializeable($value)) {
            $value = $this->serializer->serialize($value);
        }

        $query = sprintf('INSERT INTO `%s` (`key`, `value`, `ttl`, `created_on`) VALUES (:key, :value, :ttl, :created_on)', $this->table);
        $stmt = $this->pdo->prepare($query);

        return $stmt->execute(array(
            ':'.ConstProviderInterface::CACHE_PARAM_KEY =>  $key,
            ':'.ConstProviderInterface::CACHE_PARAM_VALUE => $value,
            ':'.ConstProviderInterface::CACHE_PARAM_TTL =>  $ttl,
            ':'.ConstProviderInterface::CACHE_PARAM_CREATED_ON => time(),
        ));
    }

    /**
     * Updates cache's entry
     * 
     * @param string $key
     * @param string $value
     * @param integer $ttl
     * @return boolean
     */
    public function update($key, $value, $ttl)
    {
        if ($this->serializer->isSerializeable($value)) {
            $value = $this->serializer->serialize($value);
        }

        $query = sprintf('UPDATE `%s` SET `value` =:value, `ttl`= :ttl WHERE `key` =:key', $this->table);

        $stmt = $this->pdo->prepare($query);

        return $stmt->execute(array(
            ':'.ConstProviderInterface::CACHE_PARAM_VALUE => $value,
            ':'.ConstProviderInterface::CACHE_PARAM_TTL =>  $ttl,
            ':'.ConstProviderInterface::CACHE_PARAM_KEY =>  $key,
        ));
    }

    /**
     * Removes an entry from the cache
     * 
     * @param string $key
     * @return boolean
     */
    public function delete($key)
    {
        $query = sprintf('DELETE FROM `%s` WHERE `key` = :key', $this->table);
        $stmt = $this->pdo->prepare($query);

        return $stmt->execute(array(
            ':'.ConstProviderInterface::CACHE_PARAM_KEY => $key,
        ));
    }

    /**
     * Parses result from a table
     * 
     * @param array $data
     * @return array
     */
    private function parse(array $data)
    {
        $result = array();

        foreach ($data as $index => $array) {
            if ($this->serializer->isSerialized($array[ConstProviderInterface::CACHE_PARAM_VALUE])) {
                $array[ConstProviderInterface::CACHE_PARAM_VALUE] = $this->serializer->unserialize($array[ConstProviderInterface::CACHE_PARAM_VALUE]);
            }

            $result[$array[ConstProviderInterface::CACHE_PARAM_KEY]] = array(
                ConstProviderInterface::CACHE_PARAM_VALUE => $array[ConstProviderInterface::CACHE_PARAM_VALUE], 
                ConstProviderInterface::CACHE_PARAM_TTL => $array[ConstProviderInterface::CACHE_PARAM_TTL], 
                ConstProviderInterface::CACHE_PARAM_CREATED_ON => $array[ConstProviderInterface::CACHE_PARAM_CREATED_ON]
            );
        }

        return $result;
    }
}

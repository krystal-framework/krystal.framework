<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Config\Sql;

use PDO;
use Krystal\Serializer\AbstractSerializer;

final class ConfigMapper implements ConfigMapperInterface
{
    /**
     * Serializer service
     * 
     * @var \Krystal\Serializer\AbstractSerializer
     */
    private $serializer;

    /**
     * Prepared PDO's instance
     * 
     * @var \PDO
     */
    private $pdo;

    /**
     * Table name to work with
     * 
     * @var string
     */
    private $table;

    /**
     * State initialization
     * 
     * @param \Krystal\Serializer\AbstractSerializer $serializer Serializer service
     * @param \PDO $pdo Prepared PDO instance
     * @param string $table Table to work with
     * @return void
     */
    public function __construct(AbstractSerializer $serializer, $pdo, $table)
    {
        $this->serializer = $serializer;
        $this->pdo = $pdo;
        $this->table = $table;
    }

    /**
     * Fetches all configuration data
     * 
     * @return array
     */
    public function fetchAll()
    {
        $query = sprintf('SELECT * FROM `%s`', $this->table);
        $rows = array();

        foreach ($this->pdo->query($query) as $index => $row) {
            $value =& $row[ConstProviderInterface::CONFIG_PARAM_VALUE];

            if ($this->serializer->isSerialized($value)) {
                $value = $this->serializer->unserialize($value);
            }

            array_push($rows, $row);
        }

        return $rows;
    }

    /**
     * Truncates a repository
     * 
     * @return boolean
     */
    public function truncate()
    {
        $query = sprintf('TRUNCATE `%s`', $this->table);
        return $this->pdo->exec($query);
    }

    /**
     * Inserts a new record
     * 
     * @param string $module
     * @param string $name
     * @param mixed $value
     * @return boolean
     */
    public function insert($module, $name, $value)
    {
        $query = sprintf('INSERT INTO `%s` (`module`, `name`, `value`) VALUES (:module, :name, :value)', $this->table);

        if ($this->serializer->isSerializeable($value)) {
            $value = $this->serializer->serialize($value);
        }

        $stmt = $this->pdo->prepare($query);
        return $stmt->execute(array(
            ':'.ConstProviderInterface::CONFIG_PARAM_MODULE => $module,
            ':'.ConstProviderInterface::CONFIG_PARAM_NAME => $name,
            ':'.ConstProviderInterface::CONFIG_PARAM_VALUE => $value
        ));
    }

    /**
     * Updates a record
     * 
     * @param string $module
     * @param string $name
     * @param mixed $value
     * @return boolean
     */
    public function update($module, $name, $value)
    {
        $query = sprintf('UPDATE `%s` SET `value` =:value WHERE `module` =:module AND `name` =:name', $this->table);

        if ($this->serializer->isSerializeable($value)) {
            $value = $this->serializer->serialize($value);
        }

        $stmt = $this->pdo->prepare($query);
        return $stmt->execute(array(
            ':'.ConstProviderInterface::CONFIG_PARAM_MODULE => $module,
            ':'.ConstProviderInterface::CONFIG_PARAM_NAME => $name,
            ':'.ConstProviderInterface::CONFIG_PARAM_VALUE => $value
        ));
    }

    /**
     * Deletes all configuration data by associated module
     * 
     * @param string $module
     * @return boolean
     */
    public function deleteAllByModule($module)
    {
        $query = sprintf('DELETE FROM `%s` WHERE `module` =:module', $this->table);

        $stmt = $this->pdo->prepare($query);
        return $stmt->execute(array(
            ':'.ConstProviderInterface::CONFIG_PARAM_MODULE => $module
        ));
    }

    /**
     * Deletes a record
     * 
     * @param string $module
     * @param string $name
     * @return boolean
     */
    public function delete($module, $name)
    {
        $query = sprintf('DELETE FROM `%s` WHERE `module` =:module AND `name` =:name', $this->table);

        $stmt = $this->pdo->prepare($query);
        return $stmt->execute(array(
            ':'.ConstProviderInterface::CONFIG_PARAM_MODULE => $module,
            ':'.ConstProviderInterface::CONFIG_PARAM_NAME => $name
        ));
    }
}

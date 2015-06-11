<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Db\Sql;

use InvalidArgumentException;
use PDO;

final class TableBuilder implements TableBuilderInterface
{
	/**
	 * PDO instance
	 * 
	 * @var \PDO
	 */
	private $pdo;

	/**
	 * Pending queries to be executed
	 * 
	 * @var array
	 */
	private $queries = array();

	/**
	 * State initialization
	 * 
	 * @param \PDO $pdo
	 * @param string $charset
	 * @return void
	 */
	public function __construct(PDO $pdo, $charset = 'UTF-8')
	{
		$this->pdo = $pdo;
		$this->charset = $charset;
	}

	/**
	 * Loads data from file
	 * 
	 * @param string $filename
	 * @throws \InvalidArgumentException if $filename isn't string
	 * @return void
	 */
	public function loadFromFile($filename)
	{
		if (is_file($filename)) {

			$content = file_get_contents($filename);
			$this->queries	= $this->parse($content, ';', $this->charset);

		} else {
			throw new InvalidArgumentException(sprintf(
				'Invalid argument supplied "%s" it must be a regular file', $filename
			));
		}
	}

	/**
	 * Parses SQL (that would be full of statements) string and returns an array
	 * 
	 * @param string $sql SQL string with all queries
	 * @param string $delimiter That breaks into strins
	 * @param string $charset The charset to be used while parsing
	 * @return array
	 */
	private function parse($sql, $delimiter, $charset)
	{
		$queries = explode($delimiter, $sql);
		$result  = array();

		foreach ($queries as $query) {
			// In order to avoid junk in queries,
			// That's a temporary trick
			if (mb_strlen($query, $charset) > 8) {
				array_push($result, $query);
			}
		}

		return $result;
	}

	/**
	 * Build tables
	 * 
	 * @return boolean
	 */
	public function run()
	{
		$this->pdo->beginTransaction();

		foreach ($this->queries as $query) {
			if ($this->pdo->exec($query) === false) {
				// if something went wrong, then
				$this->pdo->rollBack();
				// And
				return false;
			}
		}

		$this->pdo->commit();
		return true;
	}
}

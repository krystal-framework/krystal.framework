<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Form\Providers;

use LogicException;
use Krystal\Http\PersistentStorageInterface;

class PerPageCount extends AbstractProvider implements PerPageCountInterface
{
	/**
	 * State initialization
	 * 
	 * @param \Krystal\Http\PersistentStorageInterface $storage
	 * @param string $ns Unique namespace (i.e array key in storage)
	 * @param integer $default value to select if doesn't exist in storage
	 * @param array $values Values
	 * @throws LogicException If $default doesn't belong to $values
	 * @return void
	 */
	public function __construct(PersistentStorageInterface $storage, $ns, $default = 3, array $values = array(3, 5, 10, 15, 20, 25))
	{
		if (!in_array($default, $values)) {
			throw new LogicException(sprintf(
				'Default value must be always in collection. Provided %s which does not belong there', $default
			));
		}

		$this->storage = $storage;
		$this->ns = $ns;
		$this->default = $default;
		$this->values = $values;
	}

	/**
	 * Returns per page count options
	 * 
	 * @return array Array of option entities
	 */
	public function getPerPageCountValues()
	{
		return $this->getAllPrepared();
	}

	/**
	 * Returns current per page count
	 * 
	 * @return integer
	 */
	public function getPerPageCount()
	{
		return $this->getData();
	}

	/**
	 * Defines new per page count
	 * 
	 * @param integer $count Current count
	 * @return boolean
	 */
	public function setPerPageCount($count)
	{
		return $this->setData((int) $count);
	}
}

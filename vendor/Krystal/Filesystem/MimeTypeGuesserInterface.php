<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Filesystem;

interface MimeTypeGuesserInterface
{
	/**
	 * Finds associated type by its extension
	 * 
	 * @param string $extension
	 * @retun string
	 */
	public function getTypeByExtension($extension);
	
	/**
	 * Finds associated extension by its type
	 * 
	 * @param string $type
	 * @return string
	 */
	public function getExtensionByType($type);
	
	/**
	 * Returns full list
	 * 
	 * @param boolean $do_flip
	 * @return array
	 */
	public function getList($do_flip = false);
	
	/**
	 * Return extensions only
	 * 
	 * @return array
	 */
	public function getExtensions();
	
	/**
	 * Return types only
	 * 
	 * @return array
	 */
	public function getTypes();
	
	/**
	 * Check whether extension is valid
	 * 
	 * @param string $extension
	 * @return boolean TRUE if valid, FALSE if not
	 */
	public function isValidExtension($extension);
	
	/**
	 * Check whether type is valid
	 * 
	 * @param string $type
	 * @return boolean TRUE if valid, FALSE if not
	 */
	public function isValidType($type);
	
	/**
	 * Appends pair to the end of the stack
	 * 
	 * @param array $pair
	 * @return vod
	 */
	public function append(array $pair);
}


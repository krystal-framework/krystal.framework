<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application;

interface InputInterface
{
	/**
	 * Sets request array
	 * 
	 * @param array $request
	 * @return \Krystal\Application\Input
	 */
	public function setRequest(array &$request);

	/**
	 * Returns request array
	 * 
	 * @return array
	 */
	public function &getRequest();

	/**
	 * Sets query array
	 * 
	 * @param array $query
	 * @return \Krystal\Application\Input
	 */
	public function setQuery(array &$query);

	/**
	 * Returns query array
	 * 
	 * @return array
	 */
	public function &getQuery();

	/**	
	 * Sets posts array
	 * 
	 * @param array $post
	 * @return \Krystal\Application\Input
	 */
	public function setPost(array &$post);

	/**
	 * Returns post data
	 * 
	 * @return array
	 */
	public function &getPost();

	/**
	 * Sets files
	 * 
	 * @param array $files
	 * @return \Krystal\Application\Input
	 */
	public function setFiles(&$files);

	/**
	 * Returns files array
	 * 
	 * @return array
	 */
	public function &getFiles();

	/**
	 * Sets server's array
	 * 
	 * @param array $server
	 * @return \Krystal\Application\Input
	 */
	public function setServer(&$server);

	/**
	 * Returns server's array
	 * 
	 * @return array
	 */
	public function &getServer();

	/**
	 * Sets environment array
	 * 
	 * @param array $env
	 * @return \Krystal\Application\Input
	 */
	public function setEnv(array &$env);

	/**
	 * Returns environment array
	 * 
	 * @return array
	 */
	public function &getEnv();

	/**
	 * Sets cookie array
	 * 
	 * @param array $cookie
	 * @return \Krystal\Application\Input
	 */
	public function setCookie(array &$cookie);

	/**
	 * Returns cookie array
	 * 
	 * @return array
	 */
	public function &getCookie();
}

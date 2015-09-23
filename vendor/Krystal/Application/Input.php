<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application;

final class Input implements InputInterface
{
	/**
	 * Holds query data
	 * 
	 * @var array
	 */
	private $query = array();

	/**
	 * Holds POST variables
	 * 
	 * @var array
	 */
	private $post = array();

	/**
	 * Holds server information
	 * 
	 * @var array
	 */
	private $server = array();

	/**
	 * Holds raw files container
	 * 
	 * @var array
	 */
	private $files = array();

	/**
	 * Holds environment variables
	 * 
	 * @var array
	 */
	private $env = array();

	/**
	 * Holds native $_REQUEST
	 * 
	 * @var array
	 */
	private $request = array();

	/**
	 * Sets request array
	 * 
	 * @param array $request
	 * @return \Krystal\Application\Input
	 */
	public function setRequest(array &$request)
	{
		$this->request =& $request;
		return $this;
	}

	/**
	 * Returns request array
	 * 
	 * @return array
	 */
	public function &getRequest()
	{
		return $this->request;
	}

	/**
	 * Sets query array
	 * 
	 * @param array $query
	 * @return \Krystal\Application\Input
	 */
	public function setQuery(array &$query)
	{
		$this->query =& $query;
		return $this;
	}

	/**
	 * Returns query array
	 * 
	 * @return array
	 */
	public function &getQuery()
	{
		return $this->query;
	}

	/**
	 * Sets posts array
	 * 
	 * @param array $post
	 * @return \Krystal\Application\Input
	 */
	public function setPost(array &$post)
	{
		$this->post =& $post;
		return $this;
	}

	/**
	 * Returns post data
	 * 
	 * @return array
	 */
	public function &getPost()
	{
		return $this->post;
	}

	/**
	 * Sets files
	 * 
	 * @param array $files
	 * @return \Krystal\Application\Input
	 */
	public function setFiles(&$files)
	{
		$this->files =& $files;
		return $this;
	}

	/**
	 * Returns files array
	 * 
	 * @return array
	 */
	public function &getFiles()
	{
		return $this->files;
	}

	/**
	 * Sets server's array
	 * 
	 * @param array $server
	 * @return \Krystal\Application\Input
	 */
	public function setServer(&$server)
	{
		$this->server =& $server;
		return $this;
	}

	/**
	 * Returns server's array
	 * 
	 * @return array
	 */
	public function &getServer()
	{
		return $this->server;
	}

	/**
	 * Sets environment array
	 * 
	 * @param array $env
	 * @return \Krystal\Application\Input
	 */
	public function setEnv(array &$env)
	{
		$this->env =& $env;
		return $this;
	}

	/**
	 * Returns environment array
	 * 
	 * @return array
	 */
	public function &getEnv()
	{
		return $this->env;
	}

	/**
	 * Sets cookie array
	 * 
	 * @param array $cookie
	 * @return \Krystal\Application\Input
	 */
	public function setCookie(array &$cookie)
	{
		$this->cookie =& $cookie;
		return $this;
	}

	/**
	 * Returns cookie array
	 * 
	 * @return array
	 */
	public function &getCookie()
	{
		return $this->cookie;
	}
}

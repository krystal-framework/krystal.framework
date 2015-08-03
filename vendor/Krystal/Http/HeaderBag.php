<?php/** * This file is part of the Krystal Framework *  * Copyright (c) 2015 David Yang <daworld.ny@gmail.com> *  * For the full copyright and license information, please view * the license file that was distributed with this source code. */namespace Krystal\Http;final class HeaderBag implements HeaderBagInterface{	/**	 * Target headers	 * 	 * @var array	 */	private $headers = array();	/**	 * Clears all previous headers and adds a new one	 * 	 * @param string $header	 * @return void	 */	public function set($header)	{		$this->headers = array();		$this->append($header);	}	/**	 * Appends a header	 * 	 * @param string $header	 * @return void	 */	public function append($header)	{		array_push($this->headers, $header);	}	/**	 * Checks whether header has been appended before	 * 	 * @param string $header	 * @return boolean	 */	public function has($header)	{		return in_array($this->headers, $header);	}	/**	 * Clears the stack	 * 	 * @return array	 */	public function clear()	{		$this->headers = array();	}	/**	 * Send headers	 * 	 * @return void	 */	public function send()	{		if (headers_sent()) {			return;		}		foreach ($this->headers as $header) {			header($header);		}	}}
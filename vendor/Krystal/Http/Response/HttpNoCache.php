<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Http\Response;

use Krystal\Http\HeaderBagInterface;

final class HttpNoCache implements HttpNoCacheInterface
{
	/**
	 * Header bag to manage headers
	 * 
	 * @var \Krystal\Http\HeaderBagInterface
	 */
	private $headerBag;

	/**
	 * State initialization
	 * 
	 * @param \Krystal\Http\HeaderBagInterface $headerBag
	 * @return void
	 */
	public function __construct(HeaderBagInterface $headerBag)
	{
		$this->headerBag = $headerBag;
	}

	/**
	 * Appends required headers to disable HTTP cache
	 * 
	 * @return void
	 */
	public function configure()
	{
		$headers = array(
			'Last-Modified'	=> gmdate('D, d M Y H:i:s', strtotime('-1 day')) . ' GMT',
			'Cache-Control'	=> 'no-store, no-cache, must-revalidate',
			'Expires' => '0',
			'Pragma' =>	'no-cache'
		);

		return $this->headerBag->appendPairs($headers);
	}
}

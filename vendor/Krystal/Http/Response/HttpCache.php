<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Http\Response;

use Krystal\Http\HeaderBagInterface;

final class HttpCache implements HttpCacheInterface
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
	 * Starts to capture
	 * 
	 * @param integer $timestamp Last modified timestamp
	 * @param integer $ttl Time to live in seconds
	 * @return void
	 */
	public function configure($timestamp, $ttl)
	{
		if ($this->isModified($timestamp)) {
			$this->appendLastModified($timestamp, $ttl);
		} else {
			$this->appendNotModified($ttl);
		}
	}

	/**
	 * Appends "Not Modified" headers
	 * 
	 * @param string $maxAge Time to live in seconds
	 * @return void
	 */
	private function appendNotModified($maxAge)
	{
		$this->headerBag->setStatusCode(304)
						->appendPair('Cache-Control', sprintf('public, max-age=%s', $maxAge));
	}

	/**
	 * Appends Last-Modified headers
	 * 
	 * @param integer $timestamp Last modified timestamp
	 * @param integer $maxAge Time to live in seconds
	 * @return void
	 */
	private function appendLastModified($timestamp, $maxAge)
	{
		$headers = array(
			'Cache-Control' => sprintf('public, max-age=%s', $maxAge),
			'Last-Modified' => gmdate('D, j M Y H:i:s', $timestamp).' GMT'
		);

		$this->headerBag->setStatusCode(200)
						->appendPairs($headers);
	}

	/**
	 * Checks whether it has been modified since provided timestamp
	 * 
	 * @param integer $timestamp Last modified timestamp
	 * @return boolean
	 */
	private function isModified($timestamp)
	{
		$target = 'If-Modified-Since';

		if ($this->headerBag->hasRequestHeader($target)) {
			$sinceTimestamp = strtotime($this->headerBag->getRequestHeader($target));

			if ($sinceTimestamp != false && $timestamp <= $sinceTimestamp) {
				return false;
			}
		}

		// By default
		return true;
    }
}

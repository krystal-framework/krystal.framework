<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

class FileDownloader
{
	/**
	 * Header bag
	 * 
	 * @var object
	 */
	protected $headerBag;

	/**
	 * File system utilities
	 * 
	 * @var object
	 */
	protected $fs;

	/**
	 * Request instance
	 * 
	 * @var object
	 */
	protected $request;

	/**
	 * Class initialization
	 * 
	 * @param object $headerBag
	 * @param object $fs
	 * @return void
	 */
	public function __construct($headerBag, $fs)
	{
		$this->headerBag = $headerBag;
		$this->fs = $fs;
	}
	
	/**
	 * Prepares a thing to be send
	 * 
	 * @param string $mimeType
	 * @param string $baseName
	 * @return void
	 */
	protected function prepare($mimeType, $baseName)
	{
		// Ensure that output buffering is turned off. @ - intentionally
		@ob_end_clean();

		// if IE, otherwise Content-Disposition ignored
		if (ini_get('zlib.output_compression')) {
			ini_set('zlib.output_compression', 'Off');
		}
		
		// Prepare common headers
		$this->headerBag->setCollection(array(
			
			'Content-Type'				=>	$mimeType,
			'Content-Disposition'		=>	sprintf('attachment; filename="%s"', rawurldecode($baseName)),
			'Content-Transfer-Encoding'	=>	'binary',
			'Accept-Ranges'				=>	'bytes',
			'Cache-control'				=>	'private',
			'Pragma'					=>	'private',
			'Expires'					=>	'Thu, 26 Jul 2011 06:00:00 GMT'
		));
		
		$this->headerBag->send();
	}
	
	/**
	 * Send headers to download a file
	 * 
	 * @param string $target
	 * @param string $alias Appears as alias
	 * @return void
	 */
	public function download($target, $alias = null)
	{
		if (!is_file($target) || !is_readable($target)) {
			throw new RuntimeException('Either invalid file supplied or its not readable');
		}
		
		// Prepare base name
		if ($alias === null) {
			$baseName = $this->fs->fetchBaseName($target);
		} else {
			$baseName = $alias;
		}
		
		// Prepare output
		$this->prepare($this->fs->fetchMimeType($target), $baseName);
		
		// Count file size in bytes
		$size = filesize($target);
		
		// multipart-download and download resuming support
		if (isset($_SERVER['HTTP_RANGE'])) {
			
			list($a, $range) = explode("=", $_SERVER['HTTP_RANGE'], 2);
			list($range) = explode(",", $range, 2);
			list($range, $range_end) = explode("-", $range);
			
			$range = intval($range);
			
			if (!$range_end) {
				$range_end = $size - 1;
			} else {
				$range_end = intval($range_end);
			}
			
			$new_length = $range_end - $range + 1;
			
			header("HTTP/1.1 206 Partial Content");
			header("Content-Length: $new_length");
			header("Content-Range: bytes $range-$range_end/$size");
			
		} else {
			
			$new_length = $size;
			header("Content-Length: ".$size);
		}
		
		/* output the file itself */
		$chunksize = 1 * (1024 * 1024); // you may want to change this
		$bytes_send = 0;
		
		if ($target = fopen($target, 'r')) {
			
			if (isset($_SERVER['HTTP_RANGE'])) {
				fseek($target, $range);
			}
			
			while(!feof($target) && (!connection_aborted()) && ($bytes_send < $new_length)) {
				
				$buffer = fread($target, $chunksize);
				
				print($buffer);
				
				flush();
				
				$bytes_send += strlen($buffer);
			}
			
			fclose($target);
			
		} else { 
			
			die('Error - can not open file.'); 
		}
		
		// Successfully terminate the script
		exit(1);
	}
}

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
use Krystal\Filesystem\FileManager;
use RuntimeException;

final class FileDownloader implements FileDownloaderInterface
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
	 * Prepares initial headers to be sent with minor tweaks
	 * 
	 * @param string $mimeType
	 * @param string $baseName
	 * @return void
	 */
	private function prepare($mimeType, $baseName)
	{
		// Ensure that output buffering is turned off. @ - intentionally
		@ob_end_clean();

		// Special hack for legacy IE version
		if (ini_get('zlib.output_compression')) {
			ini_set('zlib.output_compression', 'Off');
		}

		// Prepare required headers
		$headers = array(
			'Content-Type' => $mimeType,
			'Content-Disposition' => sprintf('attachment; filename="%s"', rawurldecode($baseName)),
			'Content-Transfer-Encoding'	=> 'binary',
			'Accept-Ranges' => 'bytes',
			'Cache-control' => 'private',
			'Pragma' =>	'private',
			'Expires' => 'Thu, 21 Jul 1999 05:00:00 GMT'
		);

		$this->headerBag->appendPairs($headers)
						->send()
						->clear();
	}

	/**
	 * Preloads all required params
	 * 
	 * @param string $target
	 * @param string $alias Appears as alias
	 * @throws \RuntimeException If can't access the target file
	 * @return void
	 */
	private function preload($target, $alias)
	{
		if (!is_file($target) || !is_readable($target)) {
			throw new RuntimeException('Either invalid file supplied or its not readable');
		}

		$fm = new FileManager();

		// Prepare base name
		if ($alias === null) {
			$baseName = $fm->getBaseName($target);
		} else {
			$baseName = $alias;
		}

		// Grab the Mime-Type
		$mime = $fm->getMimeType($target);

		$this->prepare($mime, $baseName);
	}

	/**
	 * Sends downloadable headers for a file
	 * 
	 * @param string $filename A path to the target file
	 * @param string $alias Basename name can be optionally changed
	 * @throws \RuntimeException If can't access the target file
	 * @return void
	 */
	public function download($target, $alias = null)
	{
		$this->preload($target, $alias);

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

			$headers = array(
				'Content-Length' => $new_length,
				'Content-Range' => sprintf('bytes %s', $range - $range_end / $size)
			);

			$this->headerBag->setStatusCode(206)
							->setPairs($headers)
							->send();
		} else {

			$new_length = $size;
			$this->headerBag->appendPair('Content-Length', $size)
							->send()
							->clear();
		}

		$chunksize = 1024 * 1024;
		$bytes_send = 0;

		$target = fopen($target, 'r');

		if (isset($_SERVER['HTTP_RANGE'])) {
			fseek($target, $range);
		}

		while (!feof($target) && (!connection_aborted()) && ($bytes_send < $new_length)) {

			$buffer = fread($target, $chunksize);
			print($buffer);
			flush();
			$bytes_send += strlen($buffer);
		}

		fclose($target);
	}
}

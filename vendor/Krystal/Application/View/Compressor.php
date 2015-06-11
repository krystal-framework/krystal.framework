<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\View;

final class Compressor implements CompressorInterface
{
	/**
	 * Compresses content string
	 * 
	 * @param string $content
	 * @return string 
	 */
	public function compress($content)
	{
		$content = $this->removeSpaces($content);
		$content = $this->removeComments($content);
		$content = $this->removeBreaks($content);

		return $content;
	}

	/**
	 * @param string $document
	 * @return string
	 */
	private function removeSpaces($document)
	{
		return preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $document);
	}
	
	/**
	 * Removes comments from the document
	 * 
	 * @param string $document
	 * @return string
	 */
	private function removeComments($document)
	{
		return preg_replace('/<!--(.*)-->/Uis', '', $document);
	}

	/**
	 * Removes spaces, tables and line breaks
	 * 
	 * @param string $document
	 * @return string
	 */
	private function removeBreaks($document)
	{
		return str_replace(array("\r\n", "\r", "\n", "\t", '  ', '  ', '    ', '    '), '', $document);
	}
}

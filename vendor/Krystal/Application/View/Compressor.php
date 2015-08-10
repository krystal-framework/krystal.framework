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
		// Increment recursion level only before doing a compression
		ini_set('pcre.recursion_limit', '16777');

		$content = $this->removeSpaces($content);
		$content = $this->removeComments($content);

		return $content;
	}

	/**
	 * Removes all spaces from a string
	 * 
	 * @param string $content
	 * @return string
	 */
	private function removeSpaces($content)
	{
		// The pattern itself taken from here: http://stackoverflow.com/a/5324014/1208233
		// With minor modifications to support unicode and <code> tag
		$pattern = '%# Collapse whitespace everywhere but in blacklisted elements.
        (?>             # Match all whitespans other than single space.
          [^\S ]\s*     # Either one [\t\r\n\f\v] and zero or more ws,
        | \s{2,}        # or two or more consecutive-any-whitespace.
        ) # Note: The remaining regex consumes no text at all...
        (?=             # Ensure we are not in a blacklist tag.
          [^<]*+        # Either zero or more non-"<" {normal*}
          (?:           # Begin {(special normal*)*} construct
            <           # or a < starting a non-blacklist tag.
            (?!/?(?:textarea|pre|script|code)\b)
            [^<]*+      # more non-"<" {normal*}
          )*+           # Finish "unrolling-the-loop"
          (?:           # Begin alternation group.
            <           # Either a blacklist start tag.
            (?>textarea|pre|script|code)\b
          | \z          # or end of file.
          )             # End alternation group.
        )  # If we made it here, we are not in a blacklist tag.
        %Sixu';

		return preg_replace($pattern, '', $content);
	}

	/**
	 * Removes comments from the document, excluding IE's conditional ones
	 * 
	 * @param string $document
	 * @return string
	 */
	private function removeComments($content)
	{
		return preg_replace('#<!--(?!\[).*?(?!<\])-->#', '', $content);
	}
}

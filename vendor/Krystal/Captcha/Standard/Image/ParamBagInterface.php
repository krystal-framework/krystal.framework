<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Captcha\Standard\Image;

interface ParamBagInterface
{
	/**
	 * Defines a font file
	 * 
	 * @param string $font
	 * @return \Krystal\Captcha\Standard\Image\ParamBag
	 */
	public function setFontFile($fontFile);

	/**
	 * Returns font file
	 * 
	 * @return string
	 */
	public function getFontFile();

	/**
	 * Defines a text color
	 * 
	 * @param integer $textColor
	 * @return ParamBag
	 */
	public function setTextColor($textColor);

	/**
	 * Returns a text color
	 * 
	 * @return integer
	 */
	public function getTextColor();

	/**
	 * Defines a background color
	 * 
	 * @param integer $backgroundColor (Hex)
	 * @return ParamBag
	 */
	public function setBackgroundColor($backgroundColor);

	/**
	 * Returns background image (Hex)
	 * 
	 * @return integer
	 */
	public function getBackgroundColor();

	/**
	 * Defines an offset
	 * 
	 * @param integer $offset
	 * @return ParamBag
	 */
	public function setOffset($offset);

	/**
	 * Returns an offset
	 * 
	 * @return integer
	 */
	public function getOffset();

	/**
	 * Defines whether should be transparent or not
	 * 
	 * @param boolean $transparent
	 * @return ParamBag
	 */
	public function setTransparent($transparent);

	/**
	 * Tells whether transparent or not
	 * 
	 * @return boolean
	 */
	public function getTransparent();

	/**
	 * Defines a padding
	 * 
	 * @param integer $padding
	 * @return ParamBag
	 */
	public function setPadding($padding);

	/**
	 * Returns a padding
	 * 
	 * @return integer
	 */
	public function getPadding();

	/**
	 * Defines a width
	 * 
	 * @param integer $width
	 * @return ParamBag
	 */
	public function setWidth($width);

	/**
	 * Returns a width
	 * 
	 * @return integer
	 */
	public function getWidth();

	/**
	 * Defines a height
	 * 
	 * @param integer $height
	 * @return ParamBag
	 */
	public function setHeight($height);

	/**
	 * Returns a height
	 * 
	 * @return integer
	 */
	public function getHeight();
}

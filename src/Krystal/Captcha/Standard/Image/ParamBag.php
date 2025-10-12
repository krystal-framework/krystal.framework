<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Captcha\Standard\Image;

final class ParamBag implements ParamBagInterface
{
    /**
     * Width of the image
     * 
     * @var integer
     */
    private $width;

    /**
     * Height of the image
     * 
     * @var integer
     */
    private $height;

    /**
     * Padding between the text
     * 
     * @var integer
     */
    private $padding;

    /**
     * Whether image should be transparent or not
     * 
     * @var boolean
     */
    private $transparent;

    /**
     * The space between CAPTCHA characters.
     * Used in order to decrease or increase the readability of the captcha.
     * 
     * @var integer
     */
    private $offset;

    /**
     * Text color (Hex)
     * 
     * @var integer
     */
    private $textColor;

    /**
     * Hex code of the background image color
     * 
     * @var integer
     */
    private $backgroundColor;

    /**
     * Path to the text font
     * 
     * @var string
     */
    private $fontFile;

    /**
     * Defines a font file
     * 
     * @param string $font
     * @return \Krystal\Captcha\Standard\Image\ParamBag
     */
    public function setFontFile($fontFile)
    {
        $this->fontFile = $fontFile;
        return $this;
    }

    /**
     * Returns font file
     * 
     * @return string
     */
    public function getFontFile()
    {
        return $this->fontFile;
    }

    /**
     * Defines a text color
     * 
     * @param integer $textColor
     * @return \Krystal\Captcha\Standard\Image\ParamBag
     */
    public function setTextColor($textColor)
    {
        $this->textColor = $textColor;
        return $this;
    }

    /**
     * Returns a text color
     * 
     * @return integer
     */
    public function getTextColor()
    {
        return $this->textColor;
    }

    /**
     * Defines a background color
     * 
     * @param integer $backgroundColor (Hex)
     * @return \Krystal\Captcha\Standard\Image\ParamBag
     */
    public function setBackgroundColor($backgroundColor)
    {
        $this->backgroundColor = $backgroundColor;
        return $this;
    }

    /**
     * Returns background image (Hex)
     * 
     * @return integer
     */
    public function getBackgroundColor()
    {
        return $this->backgroundColor;
    }
    
    /**
     * Defines an offset
     * 
     * @param integer $offset
     * @return \Krystal\Captcha\Standard\Image\ParamBag
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * Returns an offset
     * 
     * @return integer
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * Defines whether should be transparent or not
     * 
     * @param boolean $transparent
     * @return \Krystal\Captcha\Standard\Image\ParamBag
     */
    public function setTransparent($transparent)
    {
        $this->transparent = $transparent;
        return $this;
    }

    /**
     * Tells whether transparent or not
     * 
     * @return boolean
     */
    public function getTransparent()
    {
        return $this->transparent;
    }
    
    /**
     * Defines a padding
     * 
     * @param integer $padding
     * @return \Krystal\Captcha\Standard\Image\ParamBag
     */
    public function setPadding($padding)
    {
        $this->padding = (int) $padding;
        return $this;
    }

    /**
     * Returns a padding
     * 
     * @return integer
     */
    public function getPadding()
    {
        return $this->padding;
    }

    /**
     * Defines a width
     * 
     * @param integer $width
     * @return \Krystal\Captcha\Standard\Image\ParamBag
     */
    public function setWidth($width)
    {
        $this->width = (int) $width;
        return $this;
    }

    /**
     * Returns a width
     * 
     * @return integer
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Defines a height
     * 
     * @param integer $height
     * @return \Krystal\Captcha\Standard\Image\ParamBag
     */
    public function setHeight($height)
    {
        $this->height = (int) $height;
        return $this;
    }

    /**
     * Returns a height
     * 
     * @return integer
     */
    public function getHeight()
    {
        return $this->height;
    }
}

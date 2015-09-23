<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Captcha\Standard;

use Krystal\Captcha\Standard\Image\ParamBag;
use Krystal\Captcha\Standard\Image\GD\ImageGenerator;
use Krystal\Captcha\Standard\Storage;
use Krystal\Captcha\Standard\Captcha;
use Krystal\Captcha\Standard;
use RuntimeException;
use LogicException;

final class CaptchaFactory
{
	/**
	 * Glues everything and builds prepared CAPTCHA's instance
	 * 
	 * @param array $options Optional option overrides
	 * @param \Krystal\Session\SessionBag $sessionBag Session service
	 * @throws \RuntimeException if Invalid font file name supplied
	 * @throws \LogicException if Unknown image generator supplied
	 * @return \Krystal\Captcha\Standard\Captcha
	 */
	public static function build(array $options = array(), $sessionBag = null)
	{
		if (is_null($sessionBag)) {
			$sessionBag = new StandaloneSessionBag();
		}

		// Default fonts directory
		$fontsDir = __DIR__ . '/Fonts/';
		$fontFile = isset($options['font']) ? $options['font'] : 'Arimo.ttf';

		// Since $options['font'] can be supplied by a user, the checking for existence must be done
		if (!is_file($fontsDir.$fontFile)) {
			throw new RuntimeException(sprintf('Invalid font path supplied "%s"', $fontFile));
		}

		$paramBag = new ParamBag();
		$paramBag->setWidth(isset($options['width']) ? $options['width'] : 120)
				 ->setHeight(isset($options['height']) ? $options['height'] : 50)
				 ->setPadding(isset($options['padding']) ? $options['padding'] : 3)
				 ->setTransparent(isset($options['transparent']) ? $options['transparent'] : false)
				 ->setBackgroundColor(isset($options['background_color']) ? $options['background_color'] : 0xFFFFFF) // white by default
				 ->setTextColor(isset($options['text_color']) ? $options['text_color'] : 0x3440A0) // blue by default
				 ->setOffset(isset($options['offset']) ? $options['offset'] :  -3)
				 ->setFontFile($fontsDir.$fontFile);

		$image = new ImageGenerator($paramBag);

		if (isset($options['text'])) {
			switch ($options['text']) {
				case 'math':
					$generator = new Text\Math();
				break;

				case 'number':
					$generator = new Text\RandomNumber();
				break;

				case 'fixed':
					$generator = new Text\Fixed();
				break;

				case 'random':
					$generator = new Text\RandomText();
				break;

				default:
					throw new LogicException(sprintf('Unknown image generator supplied "%s"', $options['text']));
			}

		} else {

			// That's by default as well
			$generator = new Text\RandomText();
		}

		return new Captcha($image, $generator, new Storage($sessionBag));
	}
}

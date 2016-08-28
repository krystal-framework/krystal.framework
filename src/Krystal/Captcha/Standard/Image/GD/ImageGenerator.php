<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Captcha\Standard\Image\GD;

use Krystal\Captcha\Standard\Image\ParamBagInterface;
use Krystal\Captcha\Standard\Image\ImageGeneratorInterface;
use Krystal\Captcha\Standard\Image\ImageResponse;
use RuntimeException;

if (!extension_loaded('gd') || !function_exists('imagepng')) {
    throw new RuntimeException('Image CAPTCHA requires GD extension with PNG support');
}

final class ImageGenerator implements ImageGeneratorInterface
{
    /**
     * Configuration container
     * 
     * @var \Krystal\Captcha\Standard\Image\ParamBagInterface
     */
    private $paramBag;

    /**
     * State initialization
     * 
     * @param \Krystal\Captcha\Standard\Image\ParamBagInterface $paramBag
     * @return void
     */
    public function __construct(ParamBagInterface $paramBag)
    {
        $this->paramBag = $paramBag;
    }

    /**
     * Renders the CAPTCHA
     * 
     * @param string $text Text to be rendered
     * @return void
     */
    public function render($text)
    {
        $response = new ImageResponse('image/png');
        $response->send();

        $image = $this->generate($text);

        imagepng($image);
        imagedestroy($image);
    }

    /**
     * Generates a CAPTCHA image
     * 
     * @param string $text To be rendered on generated image
     * @return resource
     */
    private function generate($text)
    {
        // The implementation of generation algorithm is base on this:
        // https://github.com/yiisoft/yii/blob/master/framework/web/widgets/captcha/CCaptchaAction.php

        // First and foremost we need to create a resource
        $image = imagecreatetruecolor($this->paramBag->getWidth(), $this->paramBag->getHeight());

        // Allocate using RBG
        $backgroundColor = imagecolorallocate(
            $image,
            (int) ($this->paramBag->getBackgroundColor() % 0x1000000 / 0x10000), 
            (int) ($this->paramBag->getBackgroundColor() % 0x10000 / 0x100), 
            (int) ($this->paramBag->getBackgroundColor() % 0x100)
        );

        // Now draw a filled rectangle
        imagefilledrectangle(
            $image, 0, 0, 
            $this->paramBag->getWidth(), 
            $this->paramBag->getHeight(), 
            $backgroundColor
        );

        // Now let's free some memory
        imagecolordeallocate($image, $backgroundColor);

        if ($this->paramBag->getTransparent() == true) {
            imagecolortransparent($image, $backgroundColor);
        }

        $textColor = imagecolorallocate(
            $image, 
            (int) ($this->paramBag->getTextColor() % 0x1000000 / 0x10000), 
            (int) ($this->paramBag->getTextColor() % 0x10000 / 0x100), 
            (int) ($this->paramBag->getTextColor() % 0x100)
        );

        $length = strlen($text);
        $box = imagettfbbox(30, 0, $this->paramBag->getFontFile(), $text);

        $w = $box[4] - $box[0] + $this->paramBag->getOffset() * ($length - 1);
        $h = $box[1] - $box[5];

        $scale = min(
            ($this->paramBag->getWidth() - $this->paramBag->getPadding() * 2) / $w, 
            ($this->paramBag->getHeight() - $this->paramBag->getPadding() * 2) / $h
        );

        $x = 10; // Width
        $y = round($this->paramBag->getHeight() * 27 / 40);

        for ($i = 0; $i < $length; ++$i) {
            $fontSize = (int) (rand(26, 32) * $scale * 0.8);
            $angle = rand(-10, 10);

            $letter = $text[$i];
            $box = imagettftext($image, $fontSize, $angle, $x, $y, $textColor, $this->paramBag->getFontFile(), $letter);
            $x = $box[2] + $this->paramBag->getOffset();
        }

        // Now free the memory
        imagecolordeallocate($image, $textColor);
        return $image;
    }
}

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Captcha\Standard;

use Krystal\Captcha\Standard\Image\ImageGeneratorInterface;
use Krystal\Captcha\Standard\Text\AbstractGenerator;
use Krystal\Captcha\Standard\StorageInterface;
use Krystal\Captcha\CaptchaInterface;

final class Captcha implements CaptchaInterface
{
    /**
     * Image generator
     * 
     * @var \Krystal\Captcha\Image\ImageGeneratorInterface
     */
    private $imageGenerator;

    /**
     * Text generator
     * 
     * @var \Krystal\Captcha\Text\AbstractGenerator
     */
    private $generator;

    /**
     * A service that stores CAPTCHA's answer
     * 
     * @var \Krystal\Captcha\Standard\StorageInterface
     */
    private $storage;

    /**
     * Error message
     * 
     * @var string
     */
    private $error;

    /**
     * State initialization
     * 
     * @param \Krystal\Captcha\Standard\Image\ImageGeneratorInterface $imageGenerator
     * @param \Krystal\Captcha\Standard\Text\AbstractGenerator $generator Any compatible text generator
     * @param \Krystal\Captcha\Standard\StorageInterface $storage A service that stores CAPTCHA's answer
     * @return void
     */
    public function __construct(ImageGeneratorInterface $imageGenerator, AbstractGenerator $generator, StorageInterface $storage)
    {
        $this->imageGenerator = $imageGenerator;
        $this->generator = $generator;
        $this->storage = $storage;
    }

    /**
     * Clears the data from a storage
     * 
     * @return void
     */
    public function clear()
    {
        return $this->storage->clear();
    }

    /**
     * Returns error message
     * 
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Checks whether CAPTCHA answer is valid
     * Should be always called after rendering
     * 
     * @param string $answer
     * @return boolean
     */
    public function isValid($answer)
    {
        // Compare shouldn't be strict, since we might compare a string against a number
        if ($this->storage->get() == $answer) {
            return true;
        } else {
            $this->error = 'Invalid answer provided';
            return false;
        }
    }

    /**
     * Renders the CAPTCHA
     * 
     * @return void
     */
    public function render()
    {
        $text = $this->generator->generate();
        $answer = $this->generator->getAnswer();

        $this->storage->set($answer);
        $this->imageGenerator->render($text);
    }
}

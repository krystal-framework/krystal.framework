<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Captcha;

/* All CAPTCHA adapters must implement this interface */
interface CaptchaInterface
{
    /**
     * Clears the data from a storage
     * 
     * @return void
     */
    public function clear();

    /**
     * Returns error message
     * 
     * @return string
     */
    public function getError();

    /**
     * Checks whether answer to the CAPTCHA is valid
     * Should be always called after rendering
     * 
     * @param string $answer
     * @return boolean
     */
    public function isValid($answer);

    /**
     * Renders the CAPTCHA
     * 
     * @return void
     */
    public function render();
}

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Captcha\Standard\Text;

final class FixedText extends AbstractGenerator
{
    /**
     * Target fixed text
     * 
     * @const string
     */
    const FIXED_TEXT = 'test';

    /**
     * {@inhertirDoc}
     */
    public function generate()
    {
        $this->setAnswer(self::FIXED_TEXT);
        return self::FIXED_TEXT;
    }
}

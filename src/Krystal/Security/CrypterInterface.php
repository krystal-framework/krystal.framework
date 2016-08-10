<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Security;

interface CrypterInterface
{
    /**
     * Encrypts a value
     * 
     * @param string $value
     * @return string
     */
    public function encryptValue($value);

    /**
     * Decrypts a value
     * 
     * @param string $value
     * @return string
     */
    public function decryptValue($value);
}

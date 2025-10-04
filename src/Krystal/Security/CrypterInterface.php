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
     * Encrypts a string
     *
     * @param string $value Plain text to encrypt
     * @return string Base64-encoded encrypted string including IV
     */
    public function encrypt($value);

    /**
     * Decrypts a string
     *
     * @param string $value Base64-encoded string produced by encrypt()
     * @return string Decrypted plain text
     */
    public function decrypt($value);
}

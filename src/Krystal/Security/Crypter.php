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

use RuntimeException;

/**
 * Crypter service for symmetric encryption and decryption using OpenSSL.
 *
 * Any arbitrary salt/key string is hashed internally to fit AES-256-CBC requirements.
 */
final class Crypter implements CrypterInterface
{
    /**
     * Encryption key derived from salt
     *
     * @var string
     */
    private $key;

    /**
     * Cipher method
     *
     * @var string
     */
    private $cipher = 'AES-256-CBC';

    /**
     * State initialization
     *
     * @param string $salt Any string used as encryption key
     * @return void
     */
    public function __construct($salt)
    {
        // Use SHA-256 to get a 32-byte key
        $this->key = hash('sha256', $salt, true);
    }

    /**
     * Encrypts a string
     *
     * @param string $value Plain text to encrypt
     * @return string Base64-encoded encrypted string including IV
     */
    public function encrypt($value)
    {
        $ivLength = openssl_cipher_iv_length($this->cipher);
        $iv = openssl_random_pseudo_bytes($ivLength);

        $encrypted = openssl_encrypt($value, $this->cipher, $this->key, \OPENSSL_RAW_DATA, $iv);

        return base64_encode($iv . $encrypted);
    }

    /**
     * Decrypts a string
     *
     * @param string $value Base64-encoded string produced by encrypt()
     * @return string Decrypted plain text
     */
    public function decrypt($value)
    {
        $data = base64_decode($value);
        $ivLength = openssl_cipher_iv_length($this->cipher);

        $iv = substr($data, 0, $ivLength);
        $encrypted = substr($data, $ivLength);

        return openssl_decrypt($encrypted, $this->cipher, $this->key, \OPENSSL_RAW_DATA, $iv);
    }
}

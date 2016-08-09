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

final class Crypter implements CrypterInterface
{
    /**
     * Shared salt for both encryption and decryption
     * 
     * @var string
     */
    private $salt;

    /**
     * Supported salt lengths
     * 
     * @var array
     */
    private $validLength = array(16, 24, 32);

    /**
     * State initialization
     * 
     * @param string $key
     * @throws \RuntimeException If the string's length doesn't match expected length
     * @return void
     */
    public function __construct($salt)
    {
        if ($this->validLength($salt)) {
            $this->salt = $salt;
        } else {
            throw new RuntimeException(
                sprintf('The length of salt must match one of these values: %s. Current one is %s', implode(', ', $this->validLength), mb_strlen($salt, 'UTF-8')
            ));
        }
    }

    /**
     * Checks whether salt's length is valid
     * 
     * @param string $salt
     * @return boolean
     */
    private function validLength($salt)
    {
        $length = mb_strlen($salt, 'UTF-8');
        return in_array($length, $this->validLength);
    }

    /**
     * Encrypts a value
     * 
     * @param string $value
     * @return string
     */
    public function encryptValue($value)
    {
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $text = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->salt, $value, MCRYPT_MODE_ECB, $iv);

        return trim(base64_encode($text));
    }

    /**
     * Decrypts a value
     * 
     * @param string $value
     * @return string
     */
    public function decryptValue($value)
    {
        $decoded = base64_decode($value);
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $text = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->salt, $decoded, MCRYPT_MODE_ECB, $iv);

        return trim($text);
    }
}

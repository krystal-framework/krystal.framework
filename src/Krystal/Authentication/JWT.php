<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Authentication;

final class JWT
{
    /**
     * Secret key for signing tokens 
     * 
     * @var string
     */
    private $secret;

    /**
     * Algorithm used (currently only HS256 supported)
     * 
     * @var string
     */
    private $algo;

    /**
     * State initialization
     *
     * @param string $secret Secret key
     * @param string $algo Algorithm (default: HS256)
     */
    public function __construct($secret, $algo = 'HS256')
    {
        $this->secret = $secret;
        $this->algo = $algo;
    }

    /**
     * Encode data to base64Url (RFC 7515)
     *
     * @param string $data
     * @return string
     */
    private function base64UrlEncode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * Decode base64Url data
     *
     * @param string $data
     * @return string|false
     */
    private function base64UrlDecode($data)
    {
        return base64_decode(strtr($data, '-_', '+/'));
    }

    /**
     * Encode a JWT token from payload
     *
     * @param array $payload Data to include in token
     * @param int $exp Expiration in seconds (default: 3600)
     * @return string
     */
    public function encode($payload, $exp = 3600)
    {
        $header = array(
            'typ' => 'JWT',
            'alg' => $this->algo
        );

        $issuedAt = time();

        $payload['iat'] = $issuedAt;
        $payload['exp'] = $issuedAt + $exp;

        $headerEnc  = $this->base64UrlEncode(json_encode($header));
        $payloadEnc = $this->base64UrlEncode(json_encode($payload));

        $signature  = hash_hmac('sha256', $headerEnc . "." . $payloadEnc, $this->secret, true);
        $signatureEnc = $this->base64UrlEncode($signature);

        return $headerEnc . "." . $payloadEnc . "." . $signatureEnc;
    }

    /**
     * Decode a JWT
     *
     * @param string $jwt The token
     * @return array|false Payload if valid, false if invalid
     */
    public function decode($jwt)
    {
        $parts = explode('.', $jwt);

        if (count($parts) !== 3) {
            return false;
        }

        list($headerEnc, $payloadEnc, $signatureEnc) = $parts;

        $expectedSignature = $this->base64UrlEncode(
            hash_hmac('sha256', $headerEnc . "." . $payloadEnc, $this->secret, true)
        );

        if (!hash_equals($expectedSignature, $signatureEnc)) {
            return false; // invalid signature
        }

        $payload = json_decode($this->base64UrlDecode($payloadEnc), true);

        if (isset($payload['exp']) && $payload['exp'] < time()) {
            return false; // expired
        }

        return $payload;
    }

    /**
     * Extracts and decodes a JWT token from the Authorization Bearer header.
     *
     * This method looks for an "Authorization: Bearer <token>" header 
     * in the current HTTP request, validates and decodes the token 
     * using the decode() method.
     *
     * @return array|false Decoded payload as an associative array if valid, 
     *                     or false if the header is missing, malformed, 
     *                     or the token is invalid/expired.
     */
    public function decodeFromBearer()
    {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? '';
        $matches = [];

        if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $decoded = $this->decode($matches[1]);

            if ($decoded) {
                return $decoded;
            }
        } else {
            return false;
        }
    }
}

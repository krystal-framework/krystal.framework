<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Http\FileTransfer;

final class UrlPathGenerator implements UrlPathGeneratorInterface
{
    /**
     * Base URL path
     * 
     * @var string
     */
    private $baseUrl;

    /**
     * State initialization
     * 
     * @param string $baseUrl
     * @return void
     */
    public function __construct($baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * Returns full URL path to a file
     * 
     * @param string $id Nested directory's id
     * @param string $filename Required filename
     * @return string
     */
    public function getPath($id, $filename)
    {
        return sprintf('%s/%s/%s', $this->baseUrl, $id, $filename);
    }
}

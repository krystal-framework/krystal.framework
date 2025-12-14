<?php
/**
 * This file is part of the Krystal Framework
 *
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Http\Client;

use RuntimeException;

final class CurlMulti
{
    /**
     * Multi cURL handle
     *
     * @var resource|\CurlMultiHandle|null
     */
    private $mh;

    /**
     * Active Curl instances keyed by handle ID
     *
     * @var array<string, Curl>
     */
    private $handles = array();

    /**
     * State initialization
     *
     * @throws \RuntimeException If multi-cURL is not available or fails to initialize
     */
    public function __construct()
    {
        if (!function_exists('curl_multi_init')) {
            throw new RuntimeException(
                'To use multi-cURL, you must have the curl extension installed'
            );
        }

        $this->mh = curl_multi_init();
        if ($this->mh === false) {
            throw new RuntimeException('Failed to initialize multi-cURL');
        }
    }

    /**
     * Prevent serialization
     *
     * @throws \RuntimeException Always
     */
    public function __sleep()
    {
        throw new RuntimeException('CurlMulti objects cannot be serialized');
    }

    /**
     * Prevent unserialization
     *
     * @throws \RuntimeException Always
     */
    public function __wakeup()
    {
        throw new RuntimeException('CurlMulti objects cannot be unserialized');
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * Add Curl instance
     *
     * @param Curl $curl
     * @throws \RuntimeException If handle is invalid or already added
     */
    public function add(Curl $curl)
    {
        $this->ensureMultiHandleValid();

        $handle = $curl->getHandle();
        $id = $this->getHandleId($handle);

        if (isset($this->handles[$id])) {
            throw new RuntimeException('This handle is already added');
        }

        $result = curl_multi_add_handle($this->mh, $handle);

        if ($result !== CURLM_OK) {
            throw new RuntimeException("Failed to add handle to multi-cURL (code: $result)");
        }

        $this->handles[$id] = $curl;
    }

    /**
     * Remove Curl instance
     *
     * @param Curl $curl
     */
    public function remove(Curl $curl)
    {
        $this->ensureMultiHandleValid();

        $handle = $curl->getHandle();
        $id = $this->getHandleId($handle);

        if (isset($this->handles[$id])) {
            curl_multi_remove_handle($this->mh, $handle);
            unset($this->handles[$id]);
        }
    }

    /**
     * Execute all requests concurrently
     *
     * @param float $selectTimeout. Defaults to 1.0
     * @return array Results keyed by internal handle ID
     * @throws \RuntimeException On execution failure
     */
    public function exec($selectTimeout = 1.0)
    {
        $this->ensureMultiHandleValid();

        if (empty($this->handles)) {
            return [];
        }

        $results = [];
        $active = null;

        // Main execution loop
        do {
            $status = curl_multi_exec($this->mh, $active);

            // Immediately process any completed transfers
            $results += $this->processCompleted();

            // Wait for activity if there are still running transfers
            if ($active > 0) {
                // Blocks efficiently until at least one socket is ready
                // Returns -1 on error, 0 on timeout (1 second default), >=1 on activity
                curl_multi_select($this->mh, $selectTimeout);
            }
        } while ($active > 0 && $status === CURLM_OK);

        if ($status !== CURLM_OK) {
            throw new RuntimeException("Multi-cURL execution failed with code: $status");
        }

        // Collect any remaining completed transfers (in case loop exited early)
        $results += $this->processCompleted();

        return $results;
    }

    /**
     * Process all currently completed transfers
     *
     * @return array Results from completed handles
     */
    private function processCompleted()
    {
        $results = [];

        while (($info = curl_multi_info_read($this->mh)) !== false) {
            $handle = $info['handle'];
            $id = $this->getHandleId($handle);

            if (!isset($this->handles[$id])) {
                // Handle already processed or unknown
                continue;
            }

            $curl = $this->handles[$id];

            // Get content BEFORE removing handle (safer)
            $content = curl_multi_getcontent($handle);

            $results[$id] = [
                'result' => $content,
                'errno'  => $curl->getErrno(),
                'error'  => $curl->getError(),
                'info'   => $curl->getInfoAll(),
            ];

            // Now safe to remove
            curl_multi_remove_handle($this->mh, $handle);
            unset($this->handles[$id]);
        }

        return $results;
    }

    /**
     * Close multi handle and clean up all attached handles
     */
    public function close()
    {
        if ($this->isMultiHandleValid()) {
            // Remove remaining handles
            foreach ($this->handles as $curl) {
                curl_multi_remove_handle($this->mh, $curl->getHandle());
            }

            curl_multi_close($this->mh);
            $this->mh = null;
            $this->handles = [];
        }
    }

    /**
     * Get unique ID for a handle (works across PHP versions)
     *
     * @param resource|\CurlHandle $handle
     * @return string
     */
    private function getHandleId($handle)
    {
        if (is_resource($handle)) {
            return 'resource_' . (int) $handle;
        }

        // PHP 8+: CurlHandle object
        return 'object_' . spl_object_hash($handle);
    }

    /**
     * Check if multi handle is valid
     *
     * @return bool
     */
    private function isMultiHandleValid()
    {
        if ($this->mh === null) {
            return false;
        }

        if (PHP_MAJOR_VERSION >= 8) {
            return $this->mh instanceof \CurlMultiHandle;
        }

        return is_resource($this->mh);
    }

    /**
     * Ensure multi handle is initialized
     *
     * @throws \RuntimeException
     */
    private function ensureMultiHandleValid()
    {
        if (!$this->isMultiHandleValid()) {
            throw new RuntimeException('Multi-cURL session has not been initialized');
        }
    }
}
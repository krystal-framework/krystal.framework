<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Cache\FileEngine;

use LogicException;
use Krystal\Serializer\AbstractSerializer;

final class CacheFile implements CacheFileInterface
{
    /**
     * Cache file location
     * 
     * @var string
     */
    private $file;

    /**
     * Whether non-existing file should be auto-created
     * 
     * @var boolean
     */
    private $autoCreate;

    /**
     * File contents
     * 
     * @var string
     */
    private $content = array();

    /**
     * Whether content is loaded
     * 
     * @var boolean
     */
    private $loaded = false;

    /**
     * Data serializer
     * 
     * @var \Krystal\Serializer\AbstractSerializer
     */
    private $serializer;

    /**
     * State initialization
     * 
     * @param \Krystal\Serializer\AbstractSerializer $serializer
     * @param string $file File path
     * @param boolean $autoCreate When to create a file automatically if it doesn't exist
     * @return void
     */
    public function __construct(AbstractSerializer $serializer, $file, $autoCreate)
    {
        $this->serializer = $serializer;
        $this->file = $file;
        $this->autoCreate = $autoCreate;
    }

    /**
     * Returns file content
     * 
     * @throws \LogicException If file hasn't been loaded before
     * @return string
     */
    public function getContent()
    {
        if ($this->loaded !== true) {
            throw new LogicException('File must be loaded before returning content');
        }

        return $this->content;
    }

    /**
     * Loads content from a file
     * 
     * @throws \RuntimeException When $autoCreate set to false and file doesn't exist
     * @return array
     */
    public function load()
    {
        // Check for existence only once and save the result
        $exists = is_file($this->file);

        if ($this->autoCreate == false && !$exists) {
            throw new RuntimeException('File does not exist');
        } else if ($this->autoCreate == true && !$exists) {
            // Create new empty file
            touch($this->file);

            $this->loaded = true;
            // There's no need to fetch content from the empty file
            return array();
        }

        $content = file_get_contents($this->file);
        $content = $this->serializer->unserialize($content);

        if (!$content) {
            $content = array();
        }

        $this->content = $content;
        $this->loaded = true;
    }

    /**
     * Writes data to the file
     * 
     * @param array $data
     * @return boolean
     */
    public function save(array $data)
    {
        return (bool) file_put_contents($this->file, $this->serializer->serialize($data));
    }
}

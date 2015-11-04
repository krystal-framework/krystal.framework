<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Form;

use Krystal\I18n\TranslatorInterface;
use Krystal\Http\PersistentStorageInterface;
use RuntimeException;

final class FlashBag implements FlashBagInterface
{
    /**
     * Any compliant storage adapter
     * 
     * @var \Krystal\Http\PersistentStorageInterface
     */
    private $storage;

    /**
     * Translator to translate messages
     * 
     * @var \Krystal\I18n\TranslatorInterface
     */
    private $translator;

    /**
     * Target flash key which acts as a namespace
     * 
     * @const string
     */
    const FLASH_KEY = 'flashMessages';

    /**
     * State initialization
     * 
     * @param \Krystal\Http\PersistentStorageInterface $storage
     * @param \Krystal\I18n\TranslatorInterface $translator
     * @return void
     */
    public function __construct(PersistentStorageInterface $storage, TranslatorInterface $translator = null)
    {
        $this->storage = $storage;
        $this->translator = $translator;

        $this->prepare();
    }

    /**
     * Prepares a messenger for usage
     * 
     * @return void
     */
    private function prepare()
    {
        if (!$this->storage->has(self::FLASH_KEY)) {
            $this->storage->set(self::FLASH_KEY, array());
        }
    }

    /**
     * Checks whether Translator has been injected
     * 
     * @return boolean
     */
    private function hasTranslator()
    {
        return $this->translator instanceof Translator;
    }

    /**
     * Checks whether message key exists
     * 
     * @param string $key
     * @return boolean
     */
    public function has($key)
    {
        $flashMessages = $this->storage->get(self::FLASH_KEY);
        return isset($flashMessages[$key]);
    }

    /**
     * Sets a message by given key name
     * 
     * @param string $key
     * @param string $message
     * @return \Krystal\Form\FlashMessenger
     */
    public function set($key, $message)
    {
        $this->storage->set(self::FLASH_KEY, array($key => $message));
        return $this;
    }

    /**
     * Removes flash key from a storage
     * 
     * @param string $key Key to be removed
     * @return void
     */
    public function remove($key)
    {
        return $this->storage->set(self::FLASH_KEY, array($key => null));
    }

    /**
     * Returns a message associated with a given key
     * 
     * @param string $key
     * @throws \RuntimeException If attempted to read non-existing key
     * @return string
     */
    public function get($key)
    {
        if ($this->has($key)) {
            $flashMessages = $this->storage->get(self::FLASH_KEY);
            $message = $flashMessages[$key];

            $this->remove($key);

            if ($this->translator instanceof TranslatorInterface) {
                $message = $this->translator->translate($message);
            }

            return $message;
        } else {
            throw new RuntimeException(sprintf(
                'Attempted to read non-existing key %s', $key
            ));
        }
    }
}

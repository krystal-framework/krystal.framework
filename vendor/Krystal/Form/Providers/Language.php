<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Form\Providers;

class Language extends AbstractProvider
{
    /**
     * Returns all languages
     * 
     * @return array
     */
    public function getAllLanguages()
    {
        return $this->getAllPrepared();
    }

    /**
     * Returns current language
     * 
     * @return string
     */
    public function getCurrentLanguage()
    {
        return $this->getData();
    }

    /**
     * Sets current language
     * 
     * @param string $language
     * @return void
     */
    public function setCurrentLanguage($language)
    {
        return $this->setData($language);
    }
}

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

interface LanguageInterface
{
    /**
     * Returns all languages
     * 
     * @return array
     */
    public function getAllLanguages();

    /**
     * Returns current language
     * 
     * @return string
     */
    public function getCurrentLanguage();

    /**
     * Sets current language
     * 
     * @param string $language
     * @return void
     */
    public function setCurrentLanguage($language);
}

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Form\Gadget;

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

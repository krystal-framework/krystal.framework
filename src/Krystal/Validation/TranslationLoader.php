<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validation;

use RuntimeException;
use InvalidArgumentException;
use Krystal\I18n\Translator;

final class TranslationLoader
{
    /**
     * Loads built-in translations into a Translator instance.
     * 
     * @param Krystal\I18n\Translator $translator
     * @param string $locale Built-in locale
     * @throws InvalidArgumentException
     */
    public static function load(Translator $translator, string $locale)
    {
        // 1. Strict pattern matching to prevent path traversal attacks
        if (!preg_match('/^[a-z_]+$/', $locale)) {
            throw new InvalidArgumentException(sprintf("Invalid locale format: '%s'", $locale));
        }

        // 2. Resolve the path relative to the library's internal structures
        $path = __DIR__ . '/Translations/' . $locale . '.php';

        if (!is_file($path)) {
            throw new InvalidArgumentException(sprintf("Built-in translation file not found for: 's%'", $locale));
        }

        $translations = include($path);

        if (!is_array($translations)) {
            throw new RuntimeException(sprintf("Translation file for '%s' did not return an array.", $locale));
        }

        $translator->extend($translations);
    }
}

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\AI\Prompt;

final class TranslationPrompt
{
    /**
     * Generates a prompt for an AI model to translate a list of strings from one language to another,
     * returning the result in JSON format where each key is the original text and the value is the translated text.
     *
     * @param array $inputArray An array of strings to be translated. Must be a flat array.
     * @param string $targetLang Target language code (e.g. 'fr', 'es', 'de').
     * @param string|null $sourceLang Optional source language code. If null, auto-detection is assumed.
     *
     * @return string A formatted prompt string to send to the AI model.
     *
     * @example
     * $input = [
     *     "Install the package.",
     *     "Ensure the network settings are correct."
     * ];
     * 
     * echo TranslationPrompt::createPrompt($input, "fr");
     */
    public static function createPrompt(array $inputList, $targetLang, $sourceLang = null)
    {
        $sourceLangText = $sourceLang ?? 'null';

        // Convert indexed array to associative array: original => ""
        $inputAssoc = array_fill_keys($inputList, "");
        $inputJson = json_encode($inputAssoc, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $systemPrompt = <<<PROMPT
You are a precise and technically accurate translation model.
Translate the **keys** in the provided JSON object from {$sourceLangText} to {$targetLang}.
If `{$sourceLangText}` is `null`, auto-detect it.

Return only a **valid JSON object** where:
- Each key is the original string from the input
- Each value is the translated string

Do **not** include any extra output — only return the translated JSON object.

Input:
{$inputJson}
PROMPT;

        return $systemPrompt;
    }
}

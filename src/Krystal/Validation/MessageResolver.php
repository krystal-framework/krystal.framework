<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validation;

use InvalidArgumentException;
use Krystal\I18n\Translator;

/**
 * Handles localization extraction and processes string interpolations safely across definitions.
 *
 * @package Krystal\Validation
 */
final class MessageResolver
{
    /**
     * An internationalization translator service dependency instance
     *
     * @var Translator|null
     */
    private $translator;

    /**
     * MessageResolver constructor.
     *
     * @param Translator|null $translator An optional translator instance to manage multi-language updates
     */
    public function __construct(Translator $translator = null)
    {
        $this->translator = $translator;
    }

    /**
     * Injects an internationalization component to resolve string translations.
     *
     * @param Translator $translator The structural translation handling service instance
     * @return void
     */
    public function setTranslator(Translator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Interpolates placeholder keys inside templates while safely converting flat array properties.
     *
     * @param string $template The base string message containing parameter insertion targets
     * @param string $label The descriptive identity name string mapping to the target input path
     * @param mixed $value The current runtime value extracted from active testing states
     * @param array $options Configuration arrays passed into rule contexts to format values
     * @return string The finalized error output string containing translated parameter items
     * @throws InvalidArgumentException When multi-dimensional arrays are supplied within parameters
     */
    public function resolve(string $template, string $label, $value, array $options): string
    {
        if ($this->translator !== null) {
            $template = $this->translator->translate($template);
        }

        $resolvedValue = '[Object Data]';
        
        if (is_scalar($value)) {
            $resolvedValue = (string) $value;
        } elseif (is_array($value)) {
            foreach ($value as $item) {
                if (is_array($item)) {
                    $resolvedValue = '[Nested Array Data]';
                    break;
                }
            }
            if ($resolvedValue !== '[Nested Array Data]') {
                $resolvedValue = implode(', ', $value);
            }
        }

        $placeholders = [
            ':attribute' => $label,
            ':value'     => $resolvedValue
        ];

        foreach ($options as $key => $val) {
            if (is_array($val)) {
                foreach ($val as $subItem) {
                    if (is_array($subItem)) {
                        throw new InvalidArgumentException(
                            "Multi-dimensional arrays are not supported for option placeholder replacements."
                        );
                    }
                }
                $placeholders[':' . $key] = implode(', ', $val);
            } else {
                $placeholders[':' . $key] = (string) $val;
            }
        }

        return strtr($template, $placeholders);
    }
}

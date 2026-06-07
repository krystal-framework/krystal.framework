<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validation;

/**
 * Tracks and configures validation rules and tracking metadata for a targeted input or file.
 *
 * @package Krystal\Validation
 */
final class Definition
{
    /**
     * The structural source key pattern tracking dot notation settings
     * 
     * @var string
     */
    private $attribute;

    /**
     * The source classification defining whether it maps to text input elements or file collections
     * 
     * @var string
     */
    private $source;

    /**
     * An explicit descriptive literal string or dynamic callback mapping to the label representation
     * 
     * @var string|callable|null
     */
    private $labelDefinition;

    /**
     * The processing setting monitoring whether structural presence requirements must be enforced
     *
     * @var bool
     */
    private $isRequired = false;

    /**
     * A collection of all validation rules linked to this specific definition
     *
     * @var array
     */
    private $rules = [];

    /**
     * Definition constructor.
     *
     * @param string $attribute The base input source path token string supporting wildcard symbols
     * @param string $source Target identity value tracking source context parameters
     * @param string|callable|null $label Option manual naming token configuration string or callback
     */
    public function __construct(string $attribute, string $source, $label = null)
    {
        $this->attribute = $attribute;
        $this->source = $source;
        $this->labelDefinition = $label;
    }

    /**
     * Marks the current definition target parameters as required during execution passes.
     *
     * @param string|null $message Custom failure text template to use instead of default definitions
     * @param bool $flag Whether the field should be required
     * @return $this
     */
    public function required(string $message = null, bool $flag = true): self
    {
        $this->isRequired = $flag;

        if ($flag) {
            $this->addRule('required', $message);
        }

        return $this;
    }

    /**
     * Assigns a custom descriptor string or real-time layout rendering callback logic for labels.
     *
     * @param string|callable $label The literal text value or custom resolution subroutine
     * @return $this Returns the fluent instance for execution rule chain formatting
     */
    public function label($label): self
    {
        $this->labelDefinition = $label;
        
        return $this;
    }

    /**
     * Extends the local definition workflow chain by logging a fresh target verification constraint block.
     *
     * @param string $rule The unique lookup tracking key identifying the verification test rule
     * @param string|null $error Manual mistake override pattern template string
     * @param array $options Configuration values mapped down directly into target execution constraints
     * @return $this Returns the fluent instance for execution rule chain formatting
     */
    public function addRule(string $rule, string $error = null, array $options = []): self
    {
        $this->rules[] = [
            'rule' => $rule,
            'message' => $error,
            'options' => $options
        ];
        
        return $this;
    }

    /**
     * Reveals the structural category indicator assigned during instance instantiation.
     *
     * @return string The context category text string
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * Exposes the internal text path mapping setting declared globally.
     *
     * @return string Target attribute identification sequence string
     */
    public function getAttribute(): string
    {
        return $this->attribute;
    }

    /**
     * Reports whether the operational requirement state tracker has been activated.
     *
     * @return bool True if requirements are active
     */
    public function isRequired(): bool
    {
        return $this->isRequired;
    }

    /**
     * Pulls the operational rules tracking sequence metadata collection array out from local structures.
     *
     * @return array Multi-dimensional collection containing operational settings
     */
    public function getRules(): array
    {
        return $this->rules;
    }

    /**
     * Extracts the stored raw label mapping reference assigned during code initialization configurations.
     *
     * @return string|callable|null Stored naming definition strings or custom processing callbacks
     */
    public function getLabelDefinition()
    {
        return $this->labelDefinition;
    }
}
<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validate;

abstract class AbstractValidator
{
    /**
     * Error messages
     * 
     * @var array
     */
    protected $errors = array();

    /**
     * Translator for messages
     * 
     * @var Translator
     */
    protected $translator;

    /**
     * Target source
     * 
     * @var array
     */
    protected $source = array();

    /**
     * Parser for definitions
     * 
     * @var DefinitionParser
     */
    protected $definitionParser;

	/**
     * Array of definitions
     * 
     * @var array
     */
    protected $definitions = array();

    /**
     * State initialization
     * 
     * @param array $source
     * @param array $definitions
     * @param DefinitionParser $definitionParser
     * @return void
     */
    final public function __construct(array $source, array $definitions, DefinitionParser $definitionParser, $translator)
    {
        $this->source = $source;
        $this->definitions = $definitions;
        $this->definitionParser = $definitionParser;
        $this->translator = $translator;
    }

	/**
     * Return all error messages
     * 
     * @param boolean $onlyMessages
     * @return array
     */
    final public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Checks whether at least one error occurred
     * 
     * @return boolean
     */
    final public function hasErrors()
    {
        return !empty($this->errors);
    }

    /**
     * {@inheritDoc}
     */ 
    final public function isValid()
    {
        $data = $this->definitionParser->parse($this->source, $this->definitions);

        foreach ($data as $target => $constraints) {
            foreach ($constraints as $constraint) {
                $value = $this->source[$target];

                // Not required and empty - don't validate. Otherwise do
                if ($constraint->getRequired() === false && !empty($value)) {
                    $constraint->setRequired(true);
                }

                // Start validation only in case the constraint has required set to true
                if ($constraint->getRequired()) {
                    // Run validation
                    if (!$constraint->isValid($value)) {
                        if (!isset($this->errors[$target])) {
                            $this->errors[$target] = array();
                        }

                        $messages = $constraint->getMessages();

                        if ($this->translator) {
                            $messages = $this->translator->translateArray($messages);
                        }

                        // Append an error message
                        array_push($this->errors[$target], $messages);

                        // If the chain should be breaked, then we gotta stop here
                        if ($constraint->getBreakable() == true) {
                            // break current nested iteration, not the main one
                            break;
                        }
                    }
                }
            }
        }

        return !$this->hasErrors();
	}
}

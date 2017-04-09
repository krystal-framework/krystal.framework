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

use Krystal\Validate\Renderer\RendererInterface;

final class ValidatorChain implements ChainInterface
{
    /**
     * Array of validators
     * 
     * @var array
     */
    private $validators = array();

    /**
     * Error messages from all attached validators
     * 
     * @var array
     */
    private $errors = array();

    /**
     * Error message renderer
     * 
     * @var \Krystal\Validate\Renderer\RendererInterface
     */
    private $renderer;

    /**
     * State initialization
     * 
     * @param array $validators
     * @param \Krystal\Validate\Renderer\RendererInterface $renderer
     * @return void
     */
    public function __construct(array $validators, RendererInterface $renderer)
    {
        $this->addValidators($validators);
        $this->renderer = $renderer;
    }

    /**
     * Checks if at least one error occurred
     * 
     * @return boolean
     */
    public function hasErrors()
    {
        return !empty($this->errors);
    }

    /**
     * Return error messages from all attached validators
     * 
     * @return array
     */
    public function getErrors()
    {
        return $this->renderer->render($this->errors);
    }

    /**
     * Runs the validation against all defined validators
     * 
     * @return boolean
     */
    public function isValid()
    {
        foreach ($this->validators as $validator) {
            if (!$validator->isValid()) {
                // Prepare error messages
                foreach ($validator->getErrors() as $name => $messages) {
                    if (!isset($this->errors[$name])) {
                        $this->errors[$name] = array();
                    }

                    $this->errors[$name] = $messages;
                }
            }
        }

        return !$this->hasErrors();
    }

    /**
     * Adds a validator
     * 
     * @param Validateable $validator
     * @return void
     */
    public function addValidators($validators)
    {
        foreach ($validators as $validator) {
            array_push($this->validators, $validator);
        }
    }
}

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validation;

/**
 * An isolated data repository tracking operational compilation errors encountered during validation steps.
 *
 * @package Krystal\Validation
 */
final class ErrorBag
{
    /**
     * A collection of all logged validation failures
     *
     * @var array
     */
    private $errors = [];

    /**
     * Records an error entry map into the local storage matrix block tracking system.
     *
     * @param array $error A structured collection mapping detailed tracking parameters for an invalid input
     * @return void
     */
    public function addError(array $error)
    {
        $this->errors[] = [
            'input'   => $error['input'],
            'label'   => $error['label'],
            'rule'    => $error['rule'],
            'message' => $error['message'],
            'value'   => $error['value'],
            'params'  => $error['params']
        ];
    }

    /**
     * Provides structured access to the full matrix of validation failure data maps.
     *
     * @return array The multi-dimensional array collection of all recorded failure states
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Empties the current error logging storage tracker repository completely.
     *
     * @return void
     */
    public function clear()
    {
        $this->errors = [];
    }

    /**
     * Informs processing engines whether failures have been successfully tracked inside local memory stores.
     *
     * @return bool True if the internal failure logs are empty
     */
    public function isEmpty()
    {
        return empty($this->errors);
    }
}

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validation;

/**
 * Manages the registration, overriding, and retrieval of built-in and user-defined validation rules.
 *
 * @package Krystal\Validation
 */
final class RuleRegistry
{
    /**
     * Registered rules dedicated to evaluating text fields
     *
     * @var array
     */
    private $fieldRules = [];

    /**
     * Registered rules dedicated to evaluating FileEntity structures
     *
     * @var array
     */
    private $fileRules = [];

    /**
     * State initialization
     * 
     * @return void
     */
    public function __construct()
    {
        $this->registerBuiltInFieldRules();
    }

    /**
     * Registers a batch collection of field checking rules inside the registry tracking pool.
     *
     * @param array $rules An array containing checking definitions mapped by rule identity names
     * @return void
     */
    public function setBuiltInFieldRules(array $rules)
    {
        foreach ($rules as $name => $config) {
            $this->fieldRules[$name] = [
                'callback' => $config['callback'],
                'message'  => $config['message']
            ];
        }
    }

    /**
     * Registers a custom rule wrapper target context inside the validation field registry pool.
     *
     * @param string $name The lookup identifier key assigned to the custom verification check
     * @param callable $callback The structural checking execution logic routine
     * @param string|null $message A default error string template to fall back on if verification fails
     * @return void
     */
    public function registerFieldRule(string $name, callable $callback, string $message = null)
    {
        if (array_key_exists($name, $this->fieldRules)) {
            trigger_error("Overriding built-in field validation rule: '" . $name . "'", E_USER_WARNING);
        }

        $this->fieldRules[$name] = [
            'callback' => $callback,
            'message'  => $message
        ];
    }

    /**
     * Registers a custom rule wrapper target context inside the validation file registry pool.
     *
     * @param string $name The lookup identifier key assigned to the custom verification check
     * @param callable $callback The structural checking execution logic routine
     * @param string|null $message A default error string template to fall back on if verification fails
     * @return void
     */
    public function registerFileRule(string $name, callable $callback, string $message = null)
    {
        if (array_key_exists($name, $this->fileRules)) {
            trigger_error("Overriding built-in file validation rule: '" . $name . "'", E_USER_WARNING);
        }

        $this->fileRules[$name] = [
            'callback' => $callback,
            'message'  => $message
        ];
    }

    /**
     * Checks if a target validation rule name exists inside the designated execution rules pool.
     *
     * @param string $source The source category name defining either field or file routing channels
     * @param string $name The unique lookup validation key identifier to verify
     * @return bool True if a matching rule wrapper configuration is actively tracked
     */
    public function hasRule(string $source, string $name): bool
    {
        $pool = ($source === 'field') ? $this->fieldRules : $this->fileRules;
        return array_key_exists($name, $pool);
    }

    /**
     * Extracts the rule executable context block metadata map from the target active pool repository.
     *
     * @param string $source The source category name defining either field or file routing channels
     * @param string $name The unique lookup validation key identifier to extract
     * @return array|null The array metadata definition context containing callback entries, or null if missing
     */
    public function getRule(string $source, string $name)
    {
        $pool = ($source === 'field') ? $this->fieldRules : $this->fileRules;
        return isset($pool[$name]) ? $pool[$name] : null;
    }

    /**
     * Seeds default baseline text parameters and validation constraints into the local tracking arrays.
     *
     * @return void
     */
    private function registerBuiltInFieldRules()
    {
        $this->setBuiltInFieldRules(include(__DIR__ . '/rules.php'));
    }
}

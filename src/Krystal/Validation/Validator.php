<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validation;

use Krystal\Validation\PathResolver;
use Krystal\Http\FileTransfer\FileEntity;
use Krystal\I18n\Translator;

/**
 * Central workflow director coordinating decoupled parsing blocks across structural inputs.
 *
 * @package Krystal\Validation
 */
final class Validator
{
    /**
     * The primary dataset array holding multi-source input parameters
     *
     * @var array
     */
    private $data;

    /**
     * The input storage matrix capturing incoming FileEntity files collections
     *
     * @var array
     */
    private $fileData;

    /**
     * Collected criteria workflows determining operations mappings metrics
     *
     * @var array
     */
    private $definitions = [];

    /**
     * Repository block managing verification rule registrations
     *
     * @var RuleRegistry
     */
    private $ruleRegistry;

    /**
     * Processing asset handling string adjustments and translations across tasks
     *
     * @var MessageResolver
     */
    private $messageResolver;

    /**
     * Centralized failure logging collection context repository instance
     *
     * @var ErrorBag
     */
    private $errorBag;

    /**
     * Determines whether execution pathways halt on an attribute line following rule faults
     *
     * @var bool
     */
    private $stopOnFailure = true;

    /**
     * Validator constructor.
     *
     * @param array $data The primary target dataset to validate
     * @param array $fileData Data parameters mapping FileEntity uploads
     */
    public function __construct(array $data, array $fileData = [])
    {
        $this->data = $data;
        $this->fileData = $fileData;
        
        $this->ruleRegistry = new RuleRegistry();
        $this->messageResolver = new MessageResolver();
        $this->errorBag = new ErrorBag();
    }

    /**
     * Toggles framework loop structures behavior when an error context step registers.
     *
     * @param bool $flag Pass true to discard trailing validation tests on a targeted key branch
     * @return $this Context orchestrator instance reference tracking calls
     */
    public function stopOnFailure(bool $flag): self
    {
        $this->stopOnFailure = $flag;
        
        return $this;
    }

    /**
     * Links an internationalization component to resolve text translations.
     *
     * @param Translator $translator The translation asset service tracking localization changes
     * @return $this Context orchestrator instance reference tracking calls
     */
    public function setTranslator(Translator $translator): self
    {
        $this->messageResolver->setTranslator($translator);
        
        return $this;
    }

    /**
     * Plugs a custom evaluation sequence inside the field check framework registries.
     *
     * @param string $name Identity lookup target labeling the verification rule
     * @param callable $callback Operational checking handler pattern statement
     * @param string|null $message Standard error notification template to assign matching failures
     * @return $this Context orchestrator instance reference tracking calls
     */
    public function setFieldRule(string $name, callable $callback, string $message = null): self
    {
        $this->ruleRegistry->registerFieldRule($name, $callback, $message);
        
        return $this;
    }

    /**
     * Plugs a custom evaluation sequence inside the file check framework registries.
     *
     * @param string $name Identity lookup target labeling the verification rule
     * @param callable $callback Operational checking handler pattern statement
     * @param string|null $message Standard error notification template to assign matching failures
     * @return $this Context orchestrator instance reference tracking calls
     */
    public function setFileRule(string $name, callable $callback, string $message = null): self
    {
        $this->ruleRegistry->registerFileRule($name, $callback, $message);
        
        return $this;
    }

    /**
     * Mounts a fluent validation targeting field parameter attributes.
     *
     * @param string $name Dot notation key index monitoring incoming string parameters
     * @param string|callable|null $label Optional descriptive identity mapping used inside notifications
     * @return Definition Stored requirements setup reference mapping updates
     */
    public function field(string $name, $label = null): Definition
    {
        $definition = new Definition($name, 'field', $label);
        $this->definitions[] = $definition;
        
        return $definition;
    }

    /**
     * Mounts a fluent validation targeting uploaded files attributes.
     *
     * @param string $name Dot notation key index monitoring incoming FileEntity parameters
     * @param string|callable|null $label Optional descriptive identity mapping used inside notifications
     * @return Definition Stored requirements setup reference mapping updates
     */
    public function file(string $name, $label = null): Definition
    {
        $definition = new Definition($name, 'file', $label);
        $this->definitions[] = $definition;
        
        return $definition;
    }

    /**
     * Iterates over active rules data structures to evaluate input parameter blocks.
     *
     * @return bool True if no input errors are found
     */
    public function isPassed(): bool
    {
        $this->errorBag->clear();

        foreach ($this->definitions as $definition) {
            $this->validateDefinition($definition);
        }

        return $this->errorBag->isEmpty();
    }

    /**
     * Collects and exposes all failure logs caught during evaluation steps.
     *
     * @return array A multi-dimensional array mapping tracked error entities
     */
    public function getErrors(): array
    {
        return $this->errorBag->getErrors();
    }

    /**
     * Evaluates data instances to determine if they meet emptiness criteria.
     *
     * @param mixed $value The resolved evaluation state component being inspected
     * @return bool True if elements match definition parameters for emptiness (excluding numeric 0 options)
     */
    private function isEmpty($value): bool
    {
        if ($value === null || $value === '') {
            return true;
        }
        
        if (is_array($value) && empty($value)) {
            return true;
        }

        if ($value instanceof FileEntity) {
            return $value->getError() === 4 || $value->getSize() === 0;
        }
        
        return false;
    }

    /**
     * Orchestrates structural checks mapping across dynamic path iterations safely.
     *
     * @param Definition $definition Active execution mapping context container definition
     * @return void
     */
    private function validateDefinition(Definition $definition)
    {
        $dataSource = ($definition->getSource() === 'field') ? $this->data : $this->fileData;
        $resolvedPaths = PathResolver::expandWildcards($dataSource, $definition->getAttribute());

        if (empty($resolvedPaths) && $definition->isRequired()) {
            $this->logError($definition->getAttribute(), $definition, [
                'rule' => 'required',
                'options' => []
            ], null);
            return;
        }

        foreach ($resolvedPaths as $path) {
            $value = PathResolver::getNestedValue($dataSource, $path);
            
            if ($this->isEmpty($value)) {
                if ($definition->isRequired()) {
                    $this->logError($path, $definition, [
                        'rule' => 'required',
                        'options' => []
                    ], $value);
                }
                continue;
            }

            foreach ($definition->getRules() as $ruleConfig) {
                if ($ruleConfig['rule'] === 'required') {
                    continue;
                }

                $passed = $this->executeRuleCallback($definition->getSource(), $ruleConfig, $value, $path);

                if (!$passed) {
                    $this->logError($path, $definition, $ruleConfig, $value);
                    if ($this->stopOnFailure) {
                        break;
                    }
                }
            }
        }
    }

    /**
     * Executes the validation rule callback by matching context pools.
     *
     * @param string $source Identifying scope channel targeting field or file loops
     * @param array $ruleConfig Operational metadata configuration tracking parameters
     * @param mixed $value Payload data item extracted from raw array structures
     * @param string $path Absolute concrete resolved location mapping parameter positions
     * @return bool True if verification check reports successful testing outcomes
     */
    private function executeRuleCallback(string $source, array $ruleConfig, $value, string $path): bool
    {
        $ruleName = $ruleConfig['rule'];
        $options = $ruleConfig['options'];

        if (!$this->ruleRegistry->hasRule($source, $ruleName)) {
            return true;
        }

        $ruleMetadata = $this->ruleRegistry->getRule($source, $ruleName);
        $callback = $ruleMetadata['callback'];
        
        $arguments = [$value, $options, $this->data, $this->fileData, $path];

        return (bool) $callback(...$arguments);
    }

    /**
     * Assembles framework items together to log contextual validation failures inside the storage component.
     *
     * @param string $path Absolute concrete internal data path location
     * @param Definition $definition Requirements structural configurations tracking setups
     * @param array $ruleConfig Specific processing rule metadata settings
     * @param mixed $value Operational testing object asset evaluated under check runs
     * @return void
     */
    private function logError(string $path, Definition $definition, array $ruleConfig, $value)
    {
        $rule = $ruleConfig['rule'];
        $options = $ruleConfig['options'];
        $label = $this->resolveLabel($path, $definition, $value);
        
        $messageTemplate = isset($ruleConfig['message']) ? $ruleConfig['message'] : null;
        
        if ($messageTemplate === null) {
            $ruleMetadata = $this->ruleRegistry->getRule($definition->getSource(), $rule);
            $messageTemplate = ($ruleMetadata !== null && isset($ruleMetadata['message']))
                ? $ruleMetadata['message']
                : "The :attribute field is invalid.";
        }

        $message = $this->messageResolver->resolve($messageTemplate, $label, $value, $options);

        $this->errorBag->addError([
            'input'   => PathResolver::toBracketNotation($path),
            'label'   => $label,
            'rule'    => $rule,
            'message' => $message,
            'value'   => $value,
            'params'  => $options
        ]);
    }

    /**
     * Normalizes text identifiers or evaluates callbacks to generate user-friendly labels.
     *
     * @param string $path Concrete target data element path string matching testing sequences
     * @param Definition $definition Workflow instructions wrapper providing metadata information
     * @param mixed $value Target element values processed inside operational loops
     * @return string Normalized display naming label text
     */
    private function resolveLabel(string $path, Definition $definition, $value): string
    {
        $def = $definition->getLabelDefinition();

        if (is_callable($def)) {
            return (string) $def($path, $value);
        }

        if (is_string($def) && $def !== '') {
            return $def;
        }

        $segments = explode('.', $path);
        return ucfirst($segments[0]);
    }
}
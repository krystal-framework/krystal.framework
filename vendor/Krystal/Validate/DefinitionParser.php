<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validate;

use RuntimeException;
use InvalidArgumentException;
use Krystal\InstanceManager\Factory;

/**
 * The point of Definition parser is to turn array configuration into something like this
 * 
 * array(
 *   'foo' => array(
 *      'preparedConstraintInstance'
 *   )
 * )
 * 
 * So that we have prepared constraint instances for defined source key.
 * That's half of work. The rest we need is to simply iterate over them in validators
 */
final class DefinitionParser implements ParserInterface
{
	/**
	 * Factory that build constraints
	 * 
	 * @var \Krystal\InstanceManager\Factory
	 */
	private $constraintFactory;

	/**
	 * State initialization
	 * 
	 * @param \Krystal\InstanceManager\Factory $constraintFactory
	 * @return void
	 */
	public function __construct(Factory $constraintFactory)
	{
		$this->constraintFactory = $constraintFactory;
	}

	/**
	 * Parse definitions
	 * Array structure specific, that looks like this:
	 * 
	 *	array(
	 *	
	 *		'input' => array(
	 *		'required' => true,
	 * 		'rules' => array(
	 *			'ConstraintName' => array(
	 *				'message' => 'Constraint message when assertion fails',
	 *              'value' => 'A string or an array of arguments to be passed to constraint's constructor'
	 *			)
	 * 		)));
	 * 
	 * @param array $source Target source input. For example $_POST. It should not be multidimensional!
	 * @param array $definitions An array of definitions for that $_POST keys
	 * @throws \RuntimeExeption if there's a key definition for non-existing corresponding $source key
	 * @return array It represents: source key name and values in array with prepared constraints
	 */
	public function parse(array $source, array $definitions)
	{
		$result = array();

		foreach ($definitions as $target => $configuration) {
			if (!isset($source[$target])) {
				throw new RuntimeException(sprintf('Invalid input name supplied "%s"', $target));
			}

			if (is_array($configuration)) {
				$this->processData($target, $configuration['rules'], $configuration['required'], $result);

				// That is a validation pattern
			} elseif (is_object($configuration)) {

				// When it's an instance, then it must be a pattern
				$configuration = $configuration->getDefinition();

				$this->processData($target, $configuration['rules'], $configuration['required'], $result);
			} else {
				throw new InvalidArgumentException('Validation rules should provide an array of definitions or a pattern of the ones');
			}
		}

		return $result;
	}

	/**
	 * Process an array of configuration
	 * 
	 * @param string $target
	 * @param array $rules
	 * @param boolean $required
	 * @param mixed $result
	 * @return void
	 */
	private function processData($target, array $rules, $required, &$result)
	{
		foreach ($rules as $constraintName => $options) {
			// Step first: Build constraint instance 
			if (isset($options['value'])) {

				// When an array is provided then we should merge values and dynamically call a method
				if (is_array($options['value'])) {

					// Quick trick
					$args = array_merge(array($constraintName), $options['value']);

					$constraint = call_user_func_array(array($this->constraintFactory, 'build'), $args);

				} else {
					$constraint = $this->constraintFactory->build($constraintName, $options['value']);
				}

			} else {

				$constraint = $this->constraintFactory->build($constraintName);
			}

			// Start tweaking the instance
			if (isset($options['break'])) {
				$constraint->setBreakable($options['break']);
			} else {
				// By default it should break the chain
				$constraint->setBreakable(true);
			}

			// If additional message specified, then use it
			// Otherwise a default constraint message is used by default
			if (isset($options['message'])) {
				$constraint->setMessage($options['message']);
			}

			$constraint->setRequired((bool) $required);

			// If a $target name was not provided before
			if (!isset($result[$target])) {
				$result[$target] = array();
			}

			// Finally add prepared constraint
			array_push($result[$target], $constraint);
		}
	}
}

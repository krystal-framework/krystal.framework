<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validate;

interface ParserInterface
{
    /**
     * Parse definitions
     * Array structure specific, that looks like this:
     * 
     *	array(
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
	public function parse(array $source, array $definitions);
}

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validate\Pattern;

abstract class AbstractUniquePattern extends AbstractPattern
{
    /**
     * Defines whether "Unique" constraint should be appended
     * 
     * @var mixed
     */
    protected $uniqueness;

    /**
     * State initialization
     * 
     * @param mixed $uniqueness If it's not null, but either true or false, then the constraint is appended
     * @param array $overrides
     * @return void
     */
    public function __construct($uniqueness = null, array $overrides = array())
    {
        $this->uniqueness = $uniqueness;
        parent::__construct($overrides);
    }

    /**
     * Returns a rules result-set with merged "Unique" constraint on demand
     * 
     * @param array $rules Initial set of validation rules
     * @param string $message The message to be display on failure
     * @return array
     */
    final protected function getMergedWithUniquenessOnDemand(array $rules, $message)
    {
        if (!is_null($this->uniqueness)) {
            $rules['Unique'] = array(
                'value' => $this->uniqueness,
                'message' => $message
            );
        }

        return $rules;
    }
}

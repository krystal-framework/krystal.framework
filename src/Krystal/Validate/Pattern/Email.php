<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validate\Pattern;

final class Email extends AbstractUniquePattern
{
    /**
     * {@inheritDoc}
     */
    public function getDefinition()
    {
        $rules = array(
            'NotEmpty' => array(
                'message' => 'Email cannot be empty',
            ),
            'EmailPattern' => array(
                'message' => 'Wrong email format supplied',
            )
        );

        $rules = $this->getMergedWithUniquenessOnDemand($rules, 'This email is already taken');

        return $this->getWithDefaults(array(
            'required' => true,
            'rules' => $rules
        ));
    }
}

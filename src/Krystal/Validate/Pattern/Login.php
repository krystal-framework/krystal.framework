<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validate\Pattern;

final class Login extends AbstractUniquePattern
{
    /**
     * {@inheritDoc}
     */
    public function getDefinition()
	{
        $rules = array(
            'NotEmpty' => array(
                'message' => 'Login can not be blank'
            ),
            'NoTags' => array(
                'message' => 'Login can not contain HTML tags'
            )
        );

        $rules = $this->getMergedWithUniquenessOnDemand($rules, 'This login is already taken');

        return $this->getWithDefaults(array(
            'required' => true,
            'rules' => $rules
        ));
    }
}

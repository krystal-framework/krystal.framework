<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validate\Pattern;

final class Phone extends AbstractPattern
{
	/**
	 * {@inheritDoc}
	 */
    public function getDefinition()
    {
        return $this->getWithDefaults(array(
            'required' => true,
            'rules' => array(
                'NotEmpty' => array(
                    'message' => 'Phone can not be blank'
                ),
                'RegExMatch' => array(
                    // Found the pattern here: http://stackoverflow.com/a/29835355/1208233
                    'value' => '/^\s*(?:\+?(\d{1,3}))?([-. (]*(\d{3})[-. )]*)?((\d{3})[-. ]*(\d{2,4})(?:[-.x ]*(\d+))?)\s*$/m',
                    'message' => 'Invalid phone format specified'
                )
            )
        ));
    }
}

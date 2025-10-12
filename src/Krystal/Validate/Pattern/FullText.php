<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validate\Pattern;

final class FullText extends AbstractPattern
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
                    'message' => 'Full description can not be empty'
                )
            )
        ));
    }
}

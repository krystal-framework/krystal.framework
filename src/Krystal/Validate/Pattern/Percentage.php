<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validate\Pattern;

final class Percentage extends AbstractPattern
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
                    'message' => 'Percentage can not be empty'
                ),
                'Numeric' => array(
                    'message' => 'Percentage can not be empty',
                ),
                'GreaterThan' => array(
                    'value' => 0,
                    'message' => 'Percentage must be greater than 0'
                ),
                'LessThan' => array(
                    'value' => 100,
                    'message' => 'Percentage must be less than 0'
                )
            )
        ));
    }
}

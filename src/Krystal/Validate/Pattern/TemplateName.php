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

final class TemplateName extends AbstractPattern
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
                    'message' => "Template's name can not be empty"
                ),
                'RegExMatch' => array(
                    'value' => '~[A-Za-z/]~',
                    'message' => 'Invalid name supplied'
                )
            )
        ));
    }
}

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validate\Pattern;

final class ImageWidth extends AbstractPattern
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
                    'message' => "Image's width can not be empty"
                ),
                'Numeric' => array(
                    'message' => "Image's width must be numeric"
                )
            )
        ));
    }
}

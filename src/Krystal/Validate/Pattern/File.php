<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validate\Pattern;

/**
 * Provides all required validation rules for a file
 */
class File extends AbstractPattern
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
                    'message' => 'Choice a file from your PC to upload'
                ),
                'WriteFail' => array(
                    'break' => false,
                ),
                'TmpDir' => array(
                    'break' => false,
                ),
                'Partial' => array(
                    'break' => false,
                ),
                'IsUploadedFile' => array(
                    'break' => false,
				),
                'IniSize' => array(
                    'break' => false,
                ),
                'FormSize' => array(
                    'break' => false,
                ),
                'FilenameMaxLength' => array(
                    'break' => false,
                )
            )
        ));
    }
}

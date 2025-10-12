<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validate\File;

use Krystal\Validate\DefinitionParser;
use Krystal\Validate\File\ConstraintFactory;
use Krystal\Validate\AbstractValidator;

final class FileValidator extends AbstractValidator
{
    /**
     * Builds an instance
     * 
     * @param array $source
     * @param array $definitions
     * @param Translator $translator
     * @return \Krystal\Validate\File\InputValidator
     */
    public static function factory(array $source, array $definitions, $translator)
    {
        return new self($source, $definitions, new DefinitionParser(new ConstraintFactory()), $translator);
    }
}

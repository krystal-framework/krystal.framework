<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Config\File;

use LogicException;

final class FileArrayType implements FileTypeInterface
{
    /**
     * {@inheritDoc}
     */
    public function render(array $config)
    {
        return "<?php\n return ".var_export($config, true).';';
    }

    /**
     * {@inheritDoc}
     */
    public function fetch($file)
    {
        $array = require($file);

        if (!is_array($array)) {
            throw new LogicException(sprintf('Configuration file "%s" must return an array', $file));
        } else {
            return $array;
        }
    }
}

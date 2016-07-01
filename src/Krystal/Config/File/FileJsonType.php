<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Config;

use LogicException;

final class FileJsonType implements FileTypeInterface
{
    /**
     * {@inheritDoc}
     */
    public function render(array $config)
    {
        return json_encode($config, true);
    }

    /**
     * {@inheritDoc}
     */
    public function fetch($file)
    {
        $json = file_get_contents($file);

        if ($json !== false) {
            return json_decode($json, true);
        } else {
            throw new LogicException(sprintf('Cannot read a file at "%s"', $file));
        }
    }
}

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application;

use Krystal\Application\App;

abstract class KernelFactory
{
    /**
     * Builds prepared application instance
     * 
     * @param array $config
     * @return \Krystal\Application\Kernel
     */
    public static function build(array $config)
    {
        $input = new Input();
        $input->setQuery($_GET)
            ->setPost($_POST)
            ->setCookie($_COOKIE)
            ->setFiles($_FILES)
            ->setServer($_SERVER)
            ->setEnv($_ENV)
            ->setRequest($_REQUEST);

        return new Kernel($input, $config);
    }
}

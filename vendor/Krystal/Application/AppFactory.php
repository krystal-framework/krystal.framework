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

// PSR-0 autoloader isn't ready yet, so gotta include these ones manually
require(__DIR__ . '/App.php');
require(__DIR__ . '/InputInterface.php');
require(__DIR__ . '/Input.php');

use Krystal\Application\App;

abstract class AppFactory
{
    /**
     * Builds prepared application instance
     * 
     * @param array $config
     * @return \Krystal\Application\App
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

        return new App($input, $config);
    }
}

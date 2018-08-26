<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Templating;

class PhpEngine
{
    /**
     * Executes PHP code and returns evaluated one as a string
     * 
     * @param string $code PHP code
     * @param array $vars
     * @return string
     */
    public static function execute($code, array $vars = array())
    {
        ob_start();

        extract($vars);
        eval('?>' . $code);

        return ob_get_clean();
    }
}

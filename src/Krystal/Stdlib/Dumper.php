<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Stdlib;

class Dumper
{
    /**
     * Dumps a variable
     * 
     * @param mixed $variable
     * @param boolean $exit Whether to terminate script execution
     * @return void
     */
    public static function dump($variable, $exit = true)
    {
        if (false === $variable) {
            var_dump($variable);
        } else {

            $text = sprintf('<pre>%s</pre>', print_r($variable, true));
            print $text;
        }

        if ($exit === true) {
            exit();
        }
    }
}

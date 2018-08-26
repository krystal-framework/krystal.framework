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

final class StringTemplate
{
    /**
     * Replaces vars (must be as {$var}) in a string
     * 
     * $input = 'Today i was about to do this at {$time} with {$name}';
     * 
     * $vars = [
     *    'time' => '13:12:00',
     *    'name' => 'Dave'
     * ];
     * 
     * echo template($input, $vars); // Today i was about to do this at 13:12:00 with Dave
     * 
     * @param string $input Input string
     * @param array $vars Variables to be replaced
     * @return string
     */
    public static function template($input, array $vars = array())
    {
        foreach ($vars as $key => $value) {
            $pattern = sprintf('{$%s}', $key);

            $input = str_replace($pattern, $value, $input);
        }

        return $input;
    }
}

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validate\Renderer;

final class MessagesOnly extends Standard
{
    /**
     * {@inheritDoc}
     */
    public function render(array $errors)
    {
        $messages = parent::render($errors);
        $result = array();

        foreach ($messages as $key => $array) {
            foreach ($array as $index => $message) {
                array_push($result, $message);
            }
        }

        return $result;
	}
}

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validate\Renderer;

final class JsonCollection extends Standard
{
    /**
     * {@inheritDoc}
     */
    public function render(array $errors)
    {
        $messages = parent::render($errors);

        // This is for returning
        $result = array(
            'messages' => array(),
            'names' => array()
        );

        foreach ($messages as $name => $messageCollection) {
            $result['messages'] = array_merge($messageCollection, $result['messages']);
            array_push($result['names'], $name);
        }

        return json_encode($result);
	}
}

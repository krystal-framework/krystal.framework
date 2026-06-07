<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

return [
    'image' => [
        'callback' => function ($file){
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
            return in_array($file->getExtension(), $allowed);
        },
        'message' => 'The :attribute must be an image (e.g., JPG, PNG, WEBP).'
    ],

    'extension' => [
        'callback' => function ($file, array $options) {
            $allowed = isset($options['allowed']) ? (array) $options['allowed'] : [];
            return in_array($file->getExtension(), $allowed, true);
        },
        'message'  => 'The system rejected the file extension attached to the target payload parameter: :attribute.'
    ]
];
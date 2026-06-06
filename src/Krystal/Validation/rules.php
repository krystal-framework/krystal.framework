<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

return [
    'required' => [
        'callback' => function ($value) {
            if (is_string($value)) {
                return trim($value) !== '';
            }
            return !empty($value) || $value === 0 || $value === '0';
        },
        'message'  => 'The :attribute field is required.'
    ],
    
    'email' => [
        'callback' => function ($value) {
            return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
        },
        'message'  => 'The :attribute must be a valid email address.'
    ],
    
    'minlen' => [
        'callback' => function ($value, array $options) {
            $min = isset($options['min']) ? (int) $options['min'] : 0;
            return mb_strlen((string) $value, 'UTF-8') >= $min;
        },
        'message'  => 'The :attribute must be at least :min characters.'
    ],
    
    'between' => [
        'callback' => function ($value, array $options) {
            $min = isset($options['min']) ? (float) $options['min'] : -INF;
            $max = isset($options['max']) ? (float) $options['max'] : INF;
            $numericVal = (float) $value;
            return $numericVal >= $min && $numericVal <= $max;
        },
        'message'  => 'The :attribute must be between :min and :max.'
    ]
];
<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

return [
    // Core framework rules
    'required' => [
        'callback' => function ($value) {
            if (is_string($value)) {
                return trim($value) !== '';
            }
            if (is_bool($value)) {
                return true; // A boolean false is still a provided state wrapper
            }
            return !empty($value) || $value === 0 || $value === '0';
        },
        'message'  => 'The :attribute field is required.'
    ],
    
    'email' => [
        'callback' => function ($value) {
            return is_string($value) && filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
        },
        'message'  => 'The :attribute must be a valid email address.'
    ],
    
    'between' => [
        'callback' => function ($value, array $options) {
            if (!is_numeric($value)) {
                return false;
            }
            $min = isset($options['min']) ? (float) $options['min'] : -INF;
            $max = isset($options['max']) ? (float) $options['max'] : INF;
            $numericVal = (float) $value;
            return $numericVal >= $min && $numericVal <= $max;
        },
        'message'  => 'The :attribute must be between :min and :max.'
    ],

    // Expanded numeric rules

    'even' => [
        'callback' => function ($value) {
            // Ensures value is numeric, has no decimal fractions, and is even
            return is_numeric($value) && floor((float) $value) == $value && ((int) $value % 2 === 0);
        },
        'message'  => 'The :attribute must be an even number.'
    ],

    'float' => [
        'callback' => function ($value) {
            return filter_var($value, FILTER_VALIDATE_FLOAT) !== false;
        },
        'message'  => 'The :attribute must be a valid floating-point number.'
    ],

    'greaterthan' => [
        'callback' => function ($value, array $options) {
            $min = isset($options['min']) ? (float) $options['min'] : 0.0;
            return is_numeric($value) && ((float) $value > $min);
        },
        'message'  => 'The :attribute must be strictly greater than :min.'
    ],

    'integer' => [
        'callback' => function ($value) {
            return filter_var($value, FILTER_VALIDATE_INT) !== false;
        },
        'message'  => 'The :attribute must be a valid integer.'
    ],

    'lessorequal' => [
        'callback' => function ($value, array $options) {
            $max = isset($options['max']) ? (float) $options['max'] : 0.0;
            return is_numeric($value) && ((float) $value <= $max);
        },
        'message'  => 'The :attribute must be less than or equal to :max.'
    ],

    'lessthan' => [
        'callback' => function ($value, array $options) {
            $max = isset($options['max']) ? (float) $options['max'] : 0.0;
            return is_numeric($value) && ((float) $value < $max);
        },
        'message'  => 'The :attribute must be strictly less than :max.'
    ],

    'negative' => [
        'callback' => function ($value) {
            return is_numeric($value) && ((float) $value < 0);
        },
        'message'  => 'The :attribute must be a negative number.'
    ],

    'numeric' => [
        'callback' => function ($value) {
            return is_numeric($value);
        },
        'message'  => 'The :attribute must be a valid number description.'
    ],

    'odd' => [
        'callback' => function ($value) {
            // Ensures value is numeric, has no decimal fractions, and is odd
            return is_numeric($value) && floor((float) $value) == $value && ((int) $value % 2 !== 0);
        },
        'message'  => 'The :attribute must be an odd number.'
    ],
    'positive' => [
        'callback' => function ($value) {
            return is_numeric($value) && ((float) $value > 0);
        },
        'message'  => 'The :attribute must be a positive number.'
    ],

    // Expanded string and charset rules
    'charset' => [
        'callback' => function ($value, array $options) {
            $encoding = isset($options['charset']) ? (string) $options['charset'] : 'UTF-8';
            return is_string($value) && mb_check_encoding($value, $encoding);
        },
        'message'  => 'The :attribute must conform to the specified :charset character encoding.'
    ],

    'contains' => [
        'callback' => function ($value, array $options) {
            $needle = isset($options['needle']) ? (string) $options['needle'] : '';
            if ($needle === '') {
                return true;
            }
            return is_string($value) && (mb_strpos($value, $needle, 0, 'UTF-8') !== false);
        },
        'message'  => 'The :attribute must contain the sub-string phrase: :needle.'
    ],

    'endswith' => [
        'callback' => function ($value, array $options) {
            $suffix = isset($options['suffix']) ? (string) $options['suffix'] : '';
            if ($suffix === '') {
                return true;
            }
            if (!is_scalar($value)) {
                return false;
            }
            $strValue = (string) $value;
            return mb_substr($strValue, -mb_strlen($suffix, 'UTF-8'), null, 'UTF-8') === $suffix;
        },
        'message'  => 'The :attribute must end with the specified suffix: :suffix.'
    ],

    'lowercase' => [
        'callback' => function ($value) {
            return is_string($value) && (mb_strtolower($value, 'UTF-8') === $value);
        },
        'message'  => 'The :attribute must contain lowercase characters only.'
    ],

    'maxlength' => [
        'callback' => function ($value, array $options) {
            if (!is_scalar($value)) {
                return false;
            }
            $max = isset($options['max']) ? (int) $options['max'] : 0;
            return mb_strlen((string) $value, 'UTF-8') <= $max;
        },
        'message'  => 'The :attribute must not exceed a maximum length of :max characters.'
    ],

    'minlength' => [
        'callback' => function ($value, array $options) {
            if (!is_scalar($value)) {
                return false;
            }
            $min = isset($options['min']) ? (int) $options['min'] : 0;
            return mb_strlen((string) $value, 'UTF-8') >= $min;
        },
        'message'  => 'The :attribute must be at least :min characters.'
    ],

    'nochar' => [
        'callback' => function ($value) {
            if (!is_scalar($value)) {
                return false;
            }
            return mb_strlen((string) $value, 'UTF-8') === 0;
        },
        'message'  => 'The :attribute field payload must not contain any text characters.'
    ],

    'notags' => [
        'callback' => function ($value) {
            if (!is_string($value)) {
                return false;
            }
            return strip_tags($value) === $value;
        },
        'message'  => 'The :attribute text string structure cannot contain HTML or XML tags.'
    ],

    'startswith' => [
        'callback' => function ($value, array $options) {
            $prefix = isset($options['prefix']) ? (string) $options['prefix'] : '';
            if ($prefix === '') {
                return true;
            }
            return is_string($value) && (mb_strpos($value, $prefix, 0, 'UTF-8') === 0);
        },
        'message'  => 'The :attribute must start with the specified prefix: :prefix.'
    ],

    'uppercase' => [
        'callback' => function ($value) {
            return is_string($value) && (mb_strtoupper($value, 'UTF-8') === $value);
        },
        'message'  => 'The :attribute must contain uppercase characters only.'
    ],

    // Presence and state
    'emptyvalue' => [
        'callback' => function ($value) {
            return empty($value) && $value !== 0 && $value !== '0' && $value !== false;
        },
        'message'  => 'The :attribute must evaluate to an empty structural state.'
    ],

    'notempty' => [
        'callback' => function ($value) {
            if (is_string($value)) {
                return trim($value) !== '';
            }
            if (is_bool($value)) {
                return true;
            }
            return !empty($value) || $value === 0 || $value === '0';
        },
        'message'  => 'The :attribute field must possess an active data state payload.'
    ],

    'notequals' => [
        'callback' => function ($value, array $options) {
            $target = isset($options['value']) ? $options['value'] : null;
            return $value !== $target;
        },
        'message'  => 'The :attribute cannot equal the specified restricted entry option value.'
    ],

    // Network, framework formats, and token patterns
    'captcha' => [
        'callback' => function ($value, array $options) {
            $expected = isset($options['expected']) ? (string) $options['expected'] : '';
            return is_string($value) && $expected !== '' && (strcasecmp(trim($value), trim($expected)) === 0);
        },
        'message'  => 'The verification security captcha entry input value is incorrect.'
    ],

    'domain' => [
        'callback' => function ($value) {
            return is_string($value) && filter_var($value, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME) !== false;
        },
        'message'  => 'The :attribute must match a valid network internet domain path description.'
    ],

    'identity' => [
        'callback' => function ($value, array $options) {
            $target = isset($options['value']) ? $options['value'] : null;
            return $value === $target;
        },
        'message'  => 'The :attribute field value must be identical to the specified comparison target.'
    ],

    'ippattern' => [
        'callback' => function ($value) {
            return is_string($value) && filter_var($value, FILTER_VALIDATE_IP) !== false;
        },
        'message'  => 'The :attribute field must resolve to a valid IPv4 or IPv6 infrastructure address.'
    ],

    'macaddress' => [
        'callback' => function ($value) {
            return is_string($value) && filter_var($value, FILTER_VALIDATE_MAC) !== false;
        },
        'message'  => 'The :attribute must match a valid hardware interface MAC address specification.'
    ],

    'regex' => [
        'callback' => function ($value, array $options) {
            $pattern = isset($options['pattern']) ? (string) $options['pattern'] : '//';
            return is_string($value) && preg_match($pattern, $value);
        },
        'message'  => 'The structural evaluation format criteria configured for :attribute is invalid.'
    ],

    'urlpattern' => [
        'callback' => function ($value) {
            return is_string($value) && filter_var($value, FILTER_VALIDATE_URL) !== false;
        },
        'message'  => 'The :attribute must resolve to a fully qualified web URL path address.'
    ],

    'xdigit' => [
        'callback' => function ($value) {
            return (is_string($value) || is_numeric($value)) && ctype_xdigit((string) $value);
        },
        'message'  => 'The :attribute can consist of valid structural hexadecimal digits only.'
    ],

    // Complex runtime data and serialization mappings
    'boolean' => [
        'callback' => function ($value) {
            return in_array($value, [true, false, 1, 0, '1', '0'], true);
        },
        'message'  => 'The :attribute field must resolve to a valid logical boolean state wrapper.'
    ],

    'callable' => [
        'callback' => function ($value) {
            return is_callable($value);
        },
        'message'  => 'The passed payload structure inside :attribute is not executable by the engine.'
    ],

    'incollection' => [
        'callback' => function ($value, array $options) {
            $collection = isset($options['collection']) ? (array) $options['collection'] : [];
            return in_array($value, $collection, true);
        },
        'message'  => 'The selected :attribute choice is outside the validated compilation index list.'
    ],

    'json' => [
        'callback' => function ($value) {
            if (!is_string($value) || trim($value) === '') {
                return false;
            }
            // Optimization: uses zero-allocation native validation engine
            return json_validate($value);
        },
        'message'  => 'The context parameters inside :attribute must constitute an un-broken JSON structure.'
    ],

    'serialized' => [
        'callback' => function ($value) {
            if (!is_string($value) || trim($value) === '') {
                return false;
            }
            if ($value === 'b:0;') {
                return true;
            }
            // Security Fix: Prevent POP/Object Injection vectors by isolating class instantiation
            return @unserialize($value, ['allowed_classes' => false]) !== false;
        },
        'message'  => 'The string stream in :attribute cannot be cleanly un-serialized into native data.'
    ],

    'unique' => [
        'callback' => function ($value, array $options, array $data) {
            $pool = isset($options['pool']) ? (array) $options['pool'] : [];
            return !in_array($value, $pool, true);
        },
        'message'  => 'The value assigned to :attribute is duplicated and violates uniqueness constraints.'
    ],

    // Chronological constraints
    'dateformat' => [
        'callback' => function ($value, array $options) {
            if (!is_string($value) && !is_numeric($value)) {
                return false;
            }
            $format = isset($options['format']) ? (string) $options['format'] : 'Y-m-d';
            $dt = \DateTime::createFromFormat($format, (string) $value);
            return $dt && $dt->format($format) === (string) $value;
        },
        'message'  => 'The calendar data format structure in :attribute does not match template: :format.'
    ],

    'dateformatmatch' => [
        'callback' => function ($value, array $options, array $data) {
            $targetField = isset($options['target']) ? (string) $options['target'] : '';
            $format = isset($options['format']) ? (string) $options['format'] : 'Y-m-d';
            if ($targetField === '' || !isset($data[$targetField]) || !is_scalar($data[$targetField]) || !is_scalar($value)) {
                return false;
            }
            $dt1 = \DateTime::createFromFormat($format, (string) $value);
            $dt2 = \DateTime::createFromFormat($format, (string) $data[$targetField]);
            return ($dt1 && $dt2 && $dt1->format($format) === (string) $value && $dt2->format($format) === (string) $data[$targetField]);
        },
        'message'  => 'The chronological layout matching sequence between :attribute and :target failed.'
    ],

    'day' => [
        'callback' => function ($value) {
            return is_numeric($value) && floor((float) $value) == $value && ((int) $value >= 1) && ((int) $value <= 31);
        },
        'message'  => 'The value assigned to :attribute must represent a standard monthly day configuration.'
    ],

    'timestamp' => [
        'callback' => function ($value) {
            if (!is_numeric($value) || floor((float) $value) != $value) {
                return false;
            }
            $timestamp = (int) $value;
            return ($timestamp >= 0) && ((string) $timestamp === (string) $value);
        },
        'message'  => 'The execution block :attribute must evaluate to a valid UNIX epoch timestamp coordinate.'
    ],

    'year' => [
        'callback' => function ($value) {
            return is_numeric($value) && floor((float) $value) == $value && ((int) $value >= 1000) && ((int) $value <= 9999);
        },
        'message'  => 'The provided timeline reference inside :attribute must match a 4-digit calendar year index.'
    ],

    // Operational server file-system checks
    'directorypath' => [
        'callback' => function ($value) {
            return is_string($value) && is_dir($value);
        },
        'message'  => 'The local execution system cannot locate a valid directory path target matching :attribute.'
    ],

    'filepath' => [
        'callback' => function ($value) {
            return is_string($value) && file_exists($value) && is_file($value);
        },
        'message'  => 'The localized storage map route configured inside :attribute is not an active target file.'
    ],

    'filereadable' => [
        'callback' => function ($value) {
            return is_string($value) && is_readable($value);
        },
        'message'  => 'The system does not possess structural file system authorization maps to read :attribute.'
    ],

    'filesize' => [
        'callback' => function ($value, array $options) {
            $maxBytes = isset($options['max']) ? (int) $options['max'] : INF;
            if (is_object($value) && method_exists($value, 'getSize')) {
                $bytes = (int) $value->getSize();
            } else {
                if (!is_scalar($value)) {
                    return false;
                }
                $bytes = (is_string($value) && file_exists($value)) ? (int) filesize($value) : (int) $value;
            }
            return $bytes <= $maxBytes;
        },
        'message'  => 'The space configuration block constraints allocated to file processing payload :attribute exceeded maximum byte values.'
    ],

    // Geospatial coordinate checking schemas
    'latitude' => [
        'callback' => function ($value) {
            return is_numeric($value) && ((float) $value >= -90.0) && ((float) $value <= 90.0);
        },
        'message'  => 'The geographical projection context index coordinate for :attribute must fall between -90 and 90 degrees.'
    ],

    'longitude' => [
        'callback' => function ($value) {
            return is_numeric($value) && ((float) $value >= -180.0) && ((float) $value <= 180.0);
        },
        'message'  => 'The geographical projection context index coordinate for :attribute must fall between -180 and 180 degrees.'
    ],
];
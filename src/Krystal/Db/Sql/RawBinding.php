<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Db\Sql;

use RuntimeException;
use InvalidArgumentException;

final class RawBinding implements RawBindingInterface
{
    /**
     * Target value considered as a raw binding
     * 
     * @var mixed
     */
    private $target;

    /**
     * State initialization
     * 
     * @param string|array $target
     * @throws \InvalidArgumentException if either non-array or non-string supplied
     * @return void
     */
    public function __construct($target)
    {
        if (is_array($target)) {
            $this->target = $this->quoteMany($target);
        } elseif (is_scalar($target)) {
            $this->target = $this->quote($target);
        } elseif (is_null($target) || empty($target)) {
            $this->target = $this->quote('');
        } else {
            throw new InvalidArgumentException(sprintf('Raw binding only accepts arrays and scalar values. Received "%s"', gettype($target)));
        }
    }

    /**
     * Returns prepared value
     * 
     * @return mixed
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * Quotes a string
     * 
     * @param string $value
     * @return string
     */
    private function quote($value)
    {
        return sprintf("'%s'", addslashes($value));
    }

    /**
     * Quotes a collection of strings
     * 
     * @param array $values
     * @return array
     */
    private function quoteMany(array $values)
    {
        foreach ($values as &$value) {
            $value = $this->quote($value);
        }

        return $values;
    }
}

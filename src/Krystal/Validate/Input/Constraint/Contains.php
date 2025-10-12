<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validate\Input\Constraint;

/**
 * Checks whether given string contain all defined characters
 */
final class Contains extends AbstractConstraint
{
    /**
     * Target char-list to compare against
     * 
     * @var array
     */
    private $charlist = array();

    /**
     * {@inheritDoc}
     */
    protected $message = 'Given string does not contain required character';

    /**
     * State initialization
     * 
     * @param mixed $charlist
     * @return void
     */
    public function __construct($charlist)
    {
        if (!is_array($charlist)) {
            $charlist = (array) $charlist;
        }

        $this->charlist = $charlist;
	}

    /**
     * {@inheritDoc}
     */
    public function isValid($target)
    {
        foreach ($this->charlist as $char) {
            if (mb_strpos($char, $target, 'UTF-8') !== false) {
                return true;
            }
        }

        // By default
        $this->violate($this->message);
        return false;
    }
}

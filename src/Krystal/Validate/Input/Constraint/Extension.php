<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validate\Input\Constraint;

/**
 * If a path contains required extension
 */
final class Extension extends AbstractConstraint
{
    /**
     * {@inheritDoc}
     */
    protected $message = 'Given path does not contain required extension';

    /**
     * Desired extension
     * 
     * @var string
     */
    private $extensions;

    /**
     * State initialization
     * 
     * @param string|array $extensions
     * @return void
     */
    public function __construct($extensions)
    {
        if (is_string($extensions)){
            $extensions = array($extensions);
        }

        $this->extensions = $extensions;
    }

    /**
     * {@inheritDoc}
     */
    public function isValid($target)
    {
        foreach ($this->extensions as $extension) {

            if ($extension !== pathinfo($target, \PATHINFO_EXTENSION)) {
                $this->violate($this->message);
                return false;
            }
        }

        return true;
    }
}

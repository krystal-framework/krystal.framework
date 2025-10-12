<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validate\File\Constraint;

final class UploadAmount extends AbstractConstraint
{
    /**
     * Amount of allowed files to be uploaded
     * 
     * @var integer
     */
    private $amount;

    /**
     * {@inheritDoc}
     */
    protected $message = 'Exceeds maximal allowed amount (%s) of files to be uploaded';

    /**
     * State initialization
     * 
     * @param integer $amount
     * @return void
     */
    public function __construct($amount)
    {
        $this->amount = $amount;
    }

	/**
	 * {@inheritDoc}
	 */
    public function isValid($files)
    {
        if (count(array_keys($files)) != $this->amount) {
            $this->violate(sprintf($this->message, $this->amount));
            return false;
        } else {
            return true;
        }
    }
}

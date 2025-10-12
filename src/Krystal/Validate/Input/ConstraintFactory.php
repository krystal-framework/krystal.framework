<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validate\Input;

use Krystal\InstanceManager\Factory;

final class ConstraintFactory extends Factory
{
    /**
     * {@ineheritDoc}
     */
    public function __construct()
    {
        $this->setNamespace('Krystal/Validate/Input/Constraint');
    }
}

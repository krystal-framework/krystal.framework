<?php/** * This file is part of the Krystal Framework *  * Copyright (c) 2015 David Yang <daworld.ny@gmail.com> *  * For the full copyright and license information, please view * the license file that was distributed with this source code. */namespace Krystal\Validate\Constraint;use Krystal\Validate\Constraint\AbstractConstraint;final class Even extends AbstractConstraint{	/**	 * {@inheritDoc}	 */	protected $message = 'A value must be even';	/**	 * {@inheritDoc}	 */	public function isValid($target)	{		if ($target % 2 === 0) {			return true;		} else {			$this->violate();			return false;		}	}}
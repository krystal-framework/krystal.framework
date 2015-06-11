<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Captcha\Standard\Text;

final class Math extends AbstractGenerator
{
	/**
	 * {@inheritDoc}
	 */
	public function generate()
	{
		$operators = array('-', '+', 'x'/*, ':'*/);
		shuffle($operators);

		// [1] is always different operator since we shuffled the array
		$operator = $operators[1];

		$a = mt_rand(1, 10);
        $b = abs(rand(1, $a - 1));

		switch ($operator) {
			case '-':
				$text = sprintf('%s - %s', $a, $b);
				$this->setAnswer($a - $b);
			break;

			case '+':
				$text = sprintf('%s + %s', $a, $b);
				$this->setAnswer($a + $b);
			break;

			case 'x':
				$text = sprintf('%s x %s', $a, $b);
				$this->setAnswer($a * $b);
			break;

			// TODO: Remainders need to be handled. That's why not used right now
			case ':':
				$text = sprintf('%s : %s', $a, $b);
				$this->setAnswer($a / $b);
			break;
		}

		return $text;
	}
}

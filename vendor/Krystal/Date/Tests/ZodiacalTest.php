<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Date\Tests;

use Krystal\Date\Zodiacal;

class ZodiacalTest extends \PHPUnit_Framework_TestCase
{
	public function testAries()
	{
		$zodiacal = new Zodiacal('March', 21);
		$this->assertTrue($zodiacal->isAries());
	}

	public function testTaurus()
	{
		$zodiacal = new Zodiacal('April', 22);
		$this->assertTrue($zodiacal->isTaurus());
	}

	public function testGemini()
	{
		$zodiacal = new Zodiacal('May', 23);
		$this->assertTrue($zodiacal->isGemini());
	}

	public function testCancer()
	{
		$zodiacal = new Zodiacal('June', 23);
		$this->assertTrue($zodiacal->isCancer());
	}

	public function testLeo()
	{
		$zodiacal = new Zodiacal('July', 23);
		$this->assertTrue($zodiacal->isLeo());
	}

	public function testVirgo()
	{
		$zodiacal = new Zodiacal('August', 29);
		$this->assertTrue($zodiacal->isVirgo());
	}

	public function testLibra()
	{
		$zodiacal = new Zodiacal('September', 25);
		$this->assertTrue($zodiacal->isLibra());
	}

	public function testScorpio()
	{
		$zodiacal = new Zodiacal('November', 21);
		$this->assertTrue($zodiacal->isScorpio());
	}

	public function testSagittarius()
	{
		$zodiacal = new Zodiacal('December', 20);
		$this->assertTrue($zodiacal->isSagittarius());
	}

	public function testCapricorn()
	{
		$zodiacal = new Zodiacal('December', 23);
		$this->assertTrue($zodiacal->isCapricorn());
	}

	public function testAquarius()
	{
		$zodiacal = new Zodiacal('January', 23);
		$this->assertTrue($zodiacal->isAquarius());
	}

	public function testPisces()
	{
		$zodiacal = new Zodiacal('February', 21);
		$this->assertTrue($zodiacal->isPisces());
	}
}

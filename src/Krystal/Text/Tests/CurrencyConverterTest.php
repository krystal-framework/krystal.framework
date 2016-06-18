<?php

namespace Krystal\Text\Tests;

use Krystal\Text\CurrencyConverter;

class CurrencyConverterTest extends \PHPUnit_Framework_TestCase
{
    private $converter;

    public function setUp()
    {
        $converter = new CurrencyConverter(array(
            'USD' => 0.7,
            'EUR' => 0.84
        ));

        $converter->set(1, 'USD');

        $this->converter = $converter;
    }

    public function testCanConvert()
    {
        $value = $this->converter->convert('EUR');
        $this->assertTrue(0.83 === $value);
    }
}

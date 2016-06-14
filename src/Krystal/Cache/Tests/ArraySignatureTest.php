<?php

namespace Krystal\Cache\Tests;

use Krystal\Cache\FileEngine\ArraySignature;

class ArraySignatureTest extends \PHPUnit_Framework_TestCase
{
    public function testCanDetectChange()
    {
        $initial = array(
            'name' => 'Dave',
            'age' => 24
        );

        $updated = array(
            'name' => 'Dave',
            'age' => 25
        );

        $singature = new ArraySignature();
        $singature->setData($initial);

        $this->assertTrue($singature->hasChanged($updated));
    }
}

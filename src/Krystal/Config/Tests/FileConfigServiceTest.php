<?php

namespace Krystal\Config\Tests;

use Krystal\Config\File;

class FileConfigServiceTest extends \PHPUnit_Framework_TestCase
{
    private $service;

    private function getTemporaryFilePath()
    {
        return __DIR__.'/file-conf-test.php';
    }

    public function setUp()
    {
        $this->service = new File\FileConfigService($this->getTemporaryFilePath(), new File\FileArrayType());
    }

    public function tearDown()
    {
        $file = $this->getTemporaryFilePath();

        if (is_file($file)) {
            chmod($file, 0777);
            unlink($file);
        }
    }

    public function testCanStore()
    {
        $this->service->store('foo', 'bar');
        $this->assertEquals($this->service->get('foo'), 'bar');
    }

    public function testCanRemove()
    {
        $this->service->store('name', 'Dave');
        $this->service->remove('name');

        $this->assertFalse($this->service->has('name'));
    }
}

<?php

namespace Krystal\I18n\Tests;

use Krystal\I18n\Translator;

class TranslatorTest extends \PHPUnit_Framework_TestCase
{
    private $translator;
    private $defaults = array(
        'To do something' => 'Zu machen etwas'
    );

    public function setUp()
    {
        $this->translator = new Translator($this->defaults);
    }

    public function testCanTranslate()
    {
        $this->assertEquals($this->translator->translate('To do something'), 'Zu machen etwas');
    }

    public function testTargetSentenceExists()
    {
        $this->assertTrue($this->translator->has('To do something'));
    }

    public function testCanBeExtended()
    {
        $this->translator->extend(array(
            'I must do it' => 'Ich muss machen das'
        ));

        $this->assertEquals($this->translator->translate('I must do it'), 'Ich muss machen das');
    }

    public function testReturnsTargetOnEmptyDictionary()
    {
        $this->translator->reset();
        $this->assertEquals($this->translator->translate('Doing a test'), 'Doing a test');
    }
}

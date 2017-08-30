<?php
namespace Componentary\Tests;

use Componentary\Element;
use PHPUnit_Framework_TestCase;

class ClassTest extends PHPUnit_Framework_TestCase
{
    public function testClass()
    {
        $e = new Element('e');
        $e->selfClose(true);

        $e->classList = ['one','two','three'];

        $this->assertTrue($e->classList->has('one'));
        $e->classList->remove('one');
        $this->assertFalse($e->classList->has('one'));

        $e->classList->add('six');
        $this->assertTrue($e->classList->has('six'));

        $this->assertTrue($e->classList->has('two'));
        $e->classList->toggle('two');
        $this->assertFalse($e->classList->has('two'));

        $this->assertEquals(
            (string) $e,
            '<e class="three six" />'
        );

        $e->classList->clear();
    }
}
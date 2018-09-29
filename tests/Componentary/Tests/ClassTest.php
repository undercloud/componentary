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

        $e->class = ['one','two','three'];

        $this->assertTrue($e->class->has('one'));
        $e->class->remove('one');
        $this->assertFalse($e->class->has('one'));

        $e->class->add('six');
        $this->assertTrue($e->class->has('six'));

        $this->assertTrue($e->class->has('two'));
        $e->class->toggle('two');
        $this->assertFalse($e->class->has('two'));

        $this->assertEquals(
            (string) $e,
            '<e class="three six" />'
        );

        $e->class->clear();

        $e->class = [
            'one',
            'two' => true,
            'three' => false
        ];

        $this->assertEquals(
           'one two',
            $e->class
        );
    }
}

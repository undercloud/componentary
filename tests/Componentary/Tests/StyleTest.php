<?php
namespace Componentary\Tests;

use Componentary\Element;
use PHPUnit_Framework_TestCase;

class StyleTest extends PHPUnit_Framework_TestCase
{
    public function testStyle()
    {
        $e = new Element('e');
        $e->selfClose(true);

        $e->style = [
            'color' => 'red',
            'fontSize' => '12px'
        ];

        $this->assertTrue($e->style->has('color'));
        $this->assertEquals('red', $e->style->color);

        $this->assertEquals(
            (string) $e,
            '<e style="color:red;font-size:12px" />'
        );

        $e->removeAttribute('style');
    }
}

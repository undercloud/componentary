<?php
namespace Componentary\Tests;

use Componentary\Invoke;
use PHPUnit_Framework_TestCase;

class InvokeTest extends PHPUnit_Framework_TestCase
{
    public function testInvoke()
    {
        $js = new Invoke('callBack');

        $this->assertEquals('callBack',$js->getName());

        $js->setArg('foo', 'bar');
        $this->assertTrue($js->hasArg('foo'));
        $this->assertEquals('bar',$js->getArg('foo'));

        $js->setArgs([
            'bar' => 'baz'
        ]);

        $js->axx = 'bzz';

        $expected = [
            'foo' => 'bar',
            'bar' => 'baz',
            'axx' => 'bzz'
        ];

        //$this->assertEquals($expected, $js->getArgs());

        $this->assertEquals("callBack('bar','baz','bzz');",(string) $js);
    }

    public function testPrototype()
    {
        $js = new Invoke('callBack', ['foo','bar','baz']);

        $js->bar = 0;
        $js->baz = 1;
        $js->foo = 2;

        $this->assertEquals("callBack(2,0,1);",(string) $js);
    }
}
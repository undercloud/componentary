<?php
namespace Componentary\Tests;

use Componentary\Element;
use PHPUnit_Framework_TestCase;

class ElementTest extends PHPUnit_Framework_TestCase
{
    public function testSimpleElement()
    {
        $iframe = new Element('iframe');
        $iframe->width = 200;
        $iframe->height = 300;
        $iframe->src = '//path.to';

        $this->assertEquals(
            (string) $iframe,
            '<iframe width="200" height="300" src="//path.to"></iframe>'
        );
    }

    public function testSelfClosing()
    {
        $img = new Element('img');
        $img->src = '//path.to';

        $this->assertEquals(
            (string) $img,
            '<img src="//path.to" />'
        );
    }

    public function testAttributes()
    {
        $e = new Element('e');

        $this->assertFalse($e->hasAttribute('id'));

        $e->id = 'my-id';
        $this->assertTrue($e->hasAttribute('id'));
        $this->assertEquals($e->id, 'my-id');

        $this->assertTrue($e->removeAttribute('id'));
        $this->assertFalse($e->hasAttribute('id'));
    }

    public function testContent()
    {
        $e = new Element('foo');
        $e->setContent('bar');
        $this->assertEquals('bar',$e->getContent());

        $e->prependChild('baz');
        $this->assertEquals('bazbar',$e->getContent());

        $e->appendChild('ban');
        $this->assertEquals('bazbarban',$e->getContent());
    }

    public function testExtraAttributes()
    {
        $e = new Element('e');
        $e->selfClose(true);

        $this->assertTrue($e->style instanceof \Componentary\Style);
        $this->assertTrue($e->classList instanceof \Componentary\ClassList);

        $e->removeAttribute('style');
        $e->removeAttribute('classList');

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
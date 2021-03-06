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
        $e->appendContent('bar');
        $this->assertEquals(['bar'], $e->getContent());

        $e->prependContent('baz');
        $this->assertEquals(['baz','bar'], $e->getContent());

        $bax = new Element('bax');
        $bax->selfClose(true);

        $e->prependChild($bax);
        $this->assertEquals([$bax, 'baz', 'bar'], $e->getContent());

        $ban = new Element('ban');
        $ban->selfClose(true);
        $e->appendChild($ban);
        $this->assertEquals([$bax, 'baz', 'bar', $ban], $e->getContent());


        $this->assertEquals('<foo><bax />bazbar<ban /></foo>', (string) $e);
    }

    public function testExtraAttributes()
    {
        $e = new Element('e');
        $e->selfClose(true);

        $this->assertTrue($e->style instanceof \Componentary\Style);
        $this->assertTrue($e->class instanceof \Componentary\ClassList);

        $e->removeAttribute('style');
        $e->removeAttribute('class');
    }
}

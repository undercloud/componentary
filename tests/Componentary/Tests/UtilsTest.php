<?php
namespace Componentary\Tests;

use Componentary\Utils;
use PHPUnit_Framework_TestCase;

class UtilsTest extends PHPUnit_Framework_TestCase
{
    public function testSimpleString()
    {
        $this->assertEquals(Utils::esc('<>"\'&'), '&lt;&gt;&quot;&#039;&amp;');
        $this->assertEquals(Utils::unesc('&lt;&gt;&quot;&#039;&amp;'), '<>"\'&');

        $this->assertEquals(Utils::stringify(true), 'true');
        $this->assertEquals(Utils::stringify(0), '0');
        $this->assertEquals(Utils::stringify(null), '');
        $this->assertEquals(Utils::stringify(''), '');
        $this->assertEquals(Utils::stringify([]), '[]');

        $this->assertTrue(Utils::isBlank(''));
        $this->assertTrue(Utils::isBlank('   '));
        $this->assertTrue(Utils::isBlank(null));
        $this->assertTrue(Utils::isBlank(false));
        $this->assertTrue(Utils::isBlank([]));
        $this->assertFalse(Utils::isBlank(0));
        $this->assertFalse(Utils::isBlank('0'));

        $this->assertTrue(Utils::isEmpty(' '));

        $this->assertEquals(Utils::unicode('\u2665\u2600'), '&#9829;&#9728;');
    }

    public function testTemplate()
    {
        $expect = 'Hello John, complete at: 89%';
        $tmpl = Utils::template('Hello {1}, complete at: {2}%', 'John', 89);

        $this->assertEquals($tmpl,$expect);
    }

    public function testManipulate()
    {
        $this->assertEquals('UPPER', Utils::upper('upper'));
        $this->assertEquals('lower', Utils::lower('LOWER'));
        $this->assertEquals('Hello', Utils::capitalize('hello'));
        $this->assertEquals('Hello', Utils::capitalize('HELLO', true));
        $this->assertEquals('Hello World', Utils::capitalizeAll('hello world'));
    }

    public function testMisc()
    {
        $this->assertEquals('L.A.', Utils::abbr('Los') . Utils::abbr('Angeles'));
        $this->assertEquals(' hello world ',Utils::whitespace('  hello  world  '));

        $this->assertEquals('1st', Utils::ordinal(1));
        $this->assertEquals('2nd', Utils::ordinal(2));
        $this->assertEquals('3rd', Utils::ordinal(3));
        $this->assertEquals('5th', Utils::ordinal(5));
    }

    public function testNum()
    {
        $this->assertEquals('12 345 678.909', Utils::number(12345678.90865, 3));

        $this->assertEquals('45.5 Mb', Utils::bytesHuman(47710208));
        $this->assertEquals('-12 Gb', Utils::bytesHuman(1024 * 1024 * 1024 * -12));

        $this->assertEquals('45.5 M', Utils::roundHuman(45.5 * 1000 * 1000));
        $this->assertEquals('-12 B', Utils::roundHuman(1000 * 1000 * 1000 * -12));
    }

    public function testAttr()
    {
        $invoke = new \Componentary\Invoke('alert');

        $url = new \Componentary\Url;
        $url->scheme = 'http';
        $url->host = 'google.com';

        $style = new \Componentary\Style;
        $style->color = 'red';

        $class = new \Componentary\ClassList;
        $class->add('main');

        $expect = (
            'id="page" ' .
            'href="http://google.com" ' .
            'style="color:red" ' .
            'class="main" ' .
            'onclick="alert();"'
        );

        $attributes = [
            'id'    => 'page',
            'href'  => $url,
            'style' => $style,
            'class' => $class,
            'onclick' => $invoke
        ];

        $this->assertEquals($expect, Utils::buildAttributes($attributes));
        $this->assertEquals($attributes, Utils::parseAttributes('<a ' . $expect . ' />')[0]);
    }

    public function testLimit()
    {
        $lorem = 'Lorem ipsum dolor sit amet';

        $this->assertEquals(Utils::limit($lorem, 10), 'Lorem ipsu...');
        $this->assertEquals(Utils::limitWords($lorem, 10), 'Lorem ipsum...');
        $this->assertEquals(Utils::limitMiddle($lorem, 12), 'Lore...amet');
    }

    public function testJson()
    {
        $this->assertEquals(Utils::stringify(new \stdClass), '{}');
        $this->assertEquals(Utils::stringify([]), '[]');
        $this->assertEquals(Utils::stringify(false), 'false');
        $this->assertEquals(Utils::stringify(''), '');
        $this->assertEquals(Utils::stringify(null), '');
        $this->assertEquals(Utils::stringify('string'), 'string');
        $this->assertEquals(Utils::stringify(new \stdClass), '{}');
    }
}

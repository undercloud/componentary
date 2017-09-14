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
        $this->assertEquals(Utils::stringify(new stdClass), '{}');
    }
}

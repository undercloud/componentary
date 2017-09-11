<?php
namespace Componentary\Tests;

use Componentary\Helper;
use PHPUnit_Framework_TestCase;

class HelperTest extends PHPUnit_Framework_TestCase
{
    public function testSimpleString()
    {
        $this->assertEquals(Helper::esc('<>"\'&'), '&lt;&gt;&quot;&#039;&amp;');
        $this->assertEquals(Helper::unesc('&lt;&gt;&quot;&#039;&amp;'), '<>"\'&');

        $this->assertEquals(Helper::stringify(true), 'true');
        $this->assertEquals(Helper::stringify(0), '0');
        $this->assertEquals(Helper::stringify(null), '');
        $this->assertEquals(Helper::stringify(''), '');
        $this->assertEquals(Helper::stringify([]), '[]');
    }
}
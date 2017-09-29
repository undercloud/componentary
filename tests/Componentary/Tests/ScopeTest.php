<?php
namespace Componentary\Tests;

use Componentary\Scope;
use PHPUnit_Framework_TestCase;

class ScopeTest extends PHPUnit_Framework_TestCase
{
    public function testScope()
    {
        Scope::set('foo', 'bar');

        $this->assertTrue(Scope::has('foo'));
        $this->assertEquals('bar', Scope::get('foo'));
        $this->assertEquals('quux', Scope::get('baz','quux'));
        $this->assertEquals('bar', Scope::getOnce('foo'));

        $this->assertFalse(Scope::has('foo'));
    }
}
?>
<?php
namespace Componentary\Tests;

use Componentary\Url;
use PHPUnit_Framework_TestCase;

class UrlTest extends PHPUnit_Framework_TestCase
{
    public function testParseUrl()
    {
        $link = "https://darth:vader@goo.gl:8080/img?foo=bar#baz";
        $url = new Url($link);

        $this->assertEquals($url->scheme, 'https');
        $this->assertEquals($url->user, 'darth');
        $this->assertEquals($url->pass, 'vader');
        $this->assertEquals($url->host, 'goo.gl');
        $this->assertEquals($url->port, '8080');
        $this->assertEquals($url->path, '/img');
        $this->assertEquals($url->query, ['foo' => 'bar']);
        $this->assertEquals($url->fragment, 'baz');
    }

    public function testBuildUrl()
    {
        $url = new Url;

        $url->scheme = 'https';
        $url->user = 'darth';
        $url->pass = 'vader';
        $url->host = 'goo.gl';
        $url->port = '8080';
        $url->path = '/img';
        $url->query = ['foo' => 'bar'];
        $url->fragment = 'baz';

        $link = "https://darth:vader@goo.gl:8080/img?foo=bar#baz";
        $this->assertEquals($link, (string) $url);
    }
}
<?php
namespace Componentary\Tests;

use Componentary\DomWalker;
use PHPUnit_Framework_TestCase;

class DomWalkerTest extends PHPUnit_Framework_TestCase
{
    public function testWalk()
    {
        $render = '%img(src="//goo.gl/img.jpg")';

        $pre = function($tag) {
            return '<' . substr($tag, 1) . '/>';
        };

        $post = function($tag){
            return str_replace(['(',')'], ' ', $tag);
        };

        DomWalker::setPreprocessor($pre);
        DomWalker::setPostprocessor($post);

        $end =  (string)
                (new DomWalker($render))
                    ->preProcess()
                    ->walk()
                    ->postProcess();


        $this->assertEquals('<img src="//goo.gl/img.jpg" />',$end);
    }

    public function testIgnore()
    {
        $render = '<User @ignore/>';

        $end =  (string)
            (new DomWalker($render))
                ->walk();

        $this->assertEquals('<User />',$end);
    }
}
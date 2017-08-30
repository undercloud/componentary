<?php

$queue = glob(__DIR__ . '/src/*.php');

foreach($queue as $q){
    require $q;
}

$js = new Componentary\Invoke('alert');
$js->sasai = ['foo' => "\n'\""];

$frame = new Componentary\Element('h1');
$frame->setContent('Lala');
$frame->onclick = $js;

echo $frame;
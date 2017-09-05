<?php
namespace {
    $queue = glob(__DIR__ . '/src/*.php');

    foreach($queue as $q){
        require $q;
    }
}

namespace Ui
{
    class User extends \Componentary\Component
    {
        public function render()
        {
            return '<img src="http://facefacts.scot/images/science/Q2_high_health_f.jpg" width="50" />';
        }
    }

    class App extends \Componentary\Component
    {
        public function render()
        {
            return '<Ui-User @ignore />';
        }
    }
}


namespace {
    //Componentary\Element::parseFrom('<a href="http://goog>gl"><b>naspine</b>ti</a>');
}
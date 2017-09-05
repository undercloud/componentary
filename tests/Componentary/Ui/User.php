<?php
namespace Componentary\Ui;

use Componentary\Component;

class User extends Component
{
    public function render()
    {
        return (
            "<span>Hello: {$this->name}</span>"
        );
    }
}
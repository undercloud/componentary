<?php
namespace Componentary\Tests;

use Componentary\Ui\User;
use PHPUnit_Framework_TestCase;

class ComponentTest extends PHPUnit_Framework_TestCase
{
    public function testComponent()
    {
        require __DIR__ . '/../Ui/User.php';

        $user = new User;
        $user->name = 'John';

        $this->assertEquals('<span>Hello: John</span>', (string) $user);
    }
}

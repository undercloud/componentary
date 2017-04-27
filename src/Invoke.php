<?php
namespace Componentary;

/**
 * External Invoke Builder
 *
 * @package  Componentary
 * @author   undercloud <lodashes@gmail.com>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     http://github.com/undercloud/componentary
 */
class Invoke
{
    private $name;

    private $args = [];

    public function __construct($name = 'undefinedInvoke')
    {
        $this->name = $name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function hasArg($name)
    {
        return isset($this->args[$name]);
    }

    public function setArg($name, $val)
    {
        $this->args[$name] = $val;

        return $this;
    }

    public function getArg($name)
    {
        if ($this->hasArg($name)) {
        return $this->args[$name];
        }
    }

    public function setArgs(array $args, $append = true)
    {
        $this->args = array_merge($append ? $this->args : [], $args);
        
        return $this;
    }

    public function getArgs()
    {
        return $this->args;
    }

    private function buildArgs()
    {
        return implode(',', array_map(function ($item) {
            switch (gettype($item)) {
            case 'NULL':
                return 'null';

            case 'boolean':
                return $item ? 'true' : 'false';
            break;

            case 'string':
                return "'{$item}'";
            break;

            case 'array':
            case 'object':
                return Helper::toJson($item);
            break;

            case 'integer':
            case 'double':
                return $item;
            break;

            default:
                return 'undefined'; 
            }
        }, $this->args));
    }

    public function __toString()
    {
        return $this->name . '(' . $this->buildArgs() . ')';
    }
}

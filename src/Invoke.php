<?php
namespace Componentary;

/**
 * External Invoke Builder
 *
 * @package Componentary
 * @author  undercloud <lodashes@gmail.com>
 * @license https://opensource.org/licenses/MIT MIT
 * @link    http://github.com/undercloud/componentary
 */
class Invoke
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $args = [];

    /**
     * @param string $name invoke
     */
    public function __construct($name = 'undefinedInvoke')
    {
        $this->name = $name;
    }

    /**
     * Set invoke name
     *
     * @param string $name key
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get invoke name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Check if argument exists
     *
     * @param string $name key
     *
     * @return boolean
     */
    public function hasArg($name)
    {
        return isset($this->args[$name]);
    }

    /**
     * Set argument
     *
     * @param string $name key
     * @param mixed  $val  value
     */
    public function setArg($name, $val)
    {
        $this->args[$name] = $val;

        return $this;
    }

    /**
     * Get argument
     *
     * @param string $name key
     *
     * @return mixed
     */
    public function getArg($name)
    {
        if ($this->hasArg($name)) {
        return $this->args[$name];
        }
    }

    /**
     * Set argument from map
     *
     * @param array   $args   array
     * @param boolean $append save setuped values
     */
    public function setArgs(array $args, $append = true)
    {
        $this->args = array_merge($append ? $this->args : [], $args);

        return $this;
    }

    /**
     * Get arguments
     *
     * @return array
     */
    public function getArgs()
    {
        return $this->args;
    }

    /**
     * Stringify arguments
     *
     * @return string
     */
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

    /**
     * __toString magic
     *
     * @return string
     */
    public function __toString()
    {
        return $this->name . '(' . $this->buildArgs() . ')';
    }
}
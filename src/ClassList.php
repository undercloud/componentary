<?php
namespace Componentary;

use Exception;

/**
 * Class helper
 *
 * @package Componentary
 * @author  undercloud <lodashes@gmail.com>
 * @license https://opensource.org/licenses/MIT MIT
 * @link    http://github.com/undercloud/componentary
 */
class ClassList
{
    /**
     * @var array
     */
    private $list = [];

    /**
     * @param array|string $list of classes
     */
    public function __construct($list = [])
    {
        if (!is_array($list) and !is_string($list)) {
            throw new Exception(sprintf(
                'Argument 1 must be array or string, %s given',
                gettype($list)
            ));
        }

        if (is_string($list)) {
            $list = explode(' ', $list);
        }

        $this->list = $list;
    }

    /**
     * Add class
     *
     * @param string $class name
     *
     * @return self
     */
    public function add($class)
    {
        if (!$this->has($class)) {
            $this->list[] = $class;
        }

        return $this;
    }

    /**
     * Check class exists
     *
     * @param string $class name
     *
     * @return boolean
     */
    public function has($class)
    {
        return in_array($class, $this->list, true);
    }

    /**
     * Remove class
     *
     * @param string $class name
     *
     * @return self
     */
    public function remove($class)
    {
        $this->list = array_diff($this->list, (array) $class);

        return $this;
    }

    /**
     * If class not exists - remove it, else add
     *
     * @param string $class name
     *
     * @return self
     */
    public function toggle($class)
    {
        if ($this->has($class)) {
            $this->remove($class);
        } else {
            $this->add($class);
        }

        return $this;
    }

    /**
     * Reset class list
     *
     * @return self
     */
    public function clear()
    {
        $this->list = [];

        return $this;
    }

    /**
     * Magic __toString
     *
     * @return string
     */
    public function __toString()
    {
        return trim(implode(' ', $this->list));
    }
}

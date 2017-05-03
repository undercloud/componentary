<?php
namespace Componentary;

/**
 * Scope storage
 *
 * @package Componentary
 * @author  undercloud <lodashes@gmail.com>
 * @license https://opensource.org/licenses/MIT MIT
 * @link    http://github.com/undercloud/componentary
 */
class Scope
{
    /**
     * @var array
     */
    protected static $map = [];

    /**
     * Extract scope
     *
     * @return array
     */
    public function all()
    {
        return self::$map;
    }

    /**
     * Push value
     *
     * @param string $key name
     * @param mixed  $val value
     *
     * @return self
     */
    public static function set($key, $val)
    {
        self::$map[$key] = $val;

        return self;
    }

    /**
     * Check key exists
     *
     * @param string $key name
     *
     * @return boolean
     */
    public static function has($key)
    {
        return isset(self::$map[$key]);
    }

    /**
     * Extract storage value
     *
     * @param string $key name
     *
     * @return mixed
     */
    public static function get($key)
    {
        if (isset(self::$map[$key])) {
            return self::$map[$key];
        }
    }

    /**
     * Clear scope
     *
     * @return self
     */
    public static function clear()
    {
        self::$map = [];

        return self;
    }
}
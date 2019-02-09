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
     * @return null
     */
    public static function set($key, $val)
    {
        self::$map[$key] = $val;
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
     * @throws RenderException
     *
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        if (isset(self::$map[$key])) {
            return self::$map[$key];
        }

        if (isset($default)) {
            return $default;
        }

        throw new RenderException('Undefined index: ' . $key);
    }

    /**
     * Extract storage value and remove it
     *
     * @param string $key     name
     * @param mixed  $default value
     *
     * @return mixed
     */
    public static function getOnce($key, $default = null)
    {
        $val = self::get($key, $default);
        self::del($key);

        return $val;
    }

    /**
     * Remove item
     *
     * @param string $key name
     *
     * @return null
     */
    public static function del($key)
    {
        unset(self::$map[$key]);
    }

    /**
     * Clear scope
     *
     * @return null
     */
    public static function clear()
    {
        self::$map = [];
    }
}

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
abstract class Component extends AbstractDom
{
    use AttributesTrait;

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @var string|null
     */
    protected static $prefix;

    /**
     * @param array $attributes map
     */
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    /**
     * Set namespace prefix
     *
     * @param string $prefix value
     *
     * @return null
     */
    public static function setPrefix($prefix)
    {
        self::$prefix = rtrim($prefix, '\\');
    }

    /**
     * Get prefix name
     *
     * @return string|null
     */
    public static function getPrefix()
    {
        return self::$prefix;
    }

    /**
     * Magic __set
     *
     * @param string $name key
     * @param string $val  value
     *
     * @return null
     */
    public function __set($name, $val)
    {
        $this->attributes[$name] = (string) $val;
    }

    /**
     * Magic __get
     *
     * @param string $name key
     *
     * @return mixed
     */
    public function __get($name)
    {
        if (isset($this->attributes[$name])) {
            return $this->attributes[$name];
        }
    }

    /**
     * Static render
     *
     * @param  string $tag value
     *
     * @return string
     */
    public static function make($tag)
    {
        return (string) (
            (new DomWalker($tag))
                ->preProcess()
                ->walk()
                ->postProcess()
        );
    }

    /**
     * Magic __toString
     *
     * @return string
     */
    public function __toString()
    {
        try {
            $render = $this->render();

            return $this->make($render);
        } catch (Exception $e) {
            return '<error>' . Utils::esc($e->getMessage()) . '</error>';
        }
    }
}

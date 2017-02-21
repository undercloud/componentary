<?php
namespace Elementary;

use Exception;

/**
 * Class helper
 *
 * @package  Elementary
 * @author   undercloud <lodashes@gmail.com>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     http://github.com/undercloud/elementary
 */
abstract class Component extends AbstractDom
{
    /**
     * @var array
     */
	protected $attrs = [];

    /**
     * @var string|null
     */
	protected static $prefix;

    /**
     * Set namespace prefix
     *
     * @param string $prefix value
     */
	public function setPrefix($prefix)
	{
		self::$prefix = rtrim($prefix, '\\');
	}

    /**
     * Get prefix name
     *
     * @return string|null
     */
	public function getPrefix()
	{
		return self::$prefix;
	}

    /**
     * Set attributes
     *
     * @param array $attrs map
     *
     * @return self
     */
	public function set(array $attrs)
	{
		$this->attrs = array_merge($this->attrs, $attrs);

		return $this;
	}

    /**
     * Check parameter exists
     *
     * @param string $attr name
     * @return boolean
     */
	public function has($attr)
	{
		return isset($this->attrs[$attr]);
	}

    /**
     * Magic __set
     *
     * @param string $attr name
     * @param string $val  value
     */
	public function __set($attr, $val)
	{
		$this->attrs[$attr] = (string) $val;
	}

    /**
     * Magic __get
     *
     * @param string $attr name
     *
     * @return mixed
     */
	public function __get($attr)
	{
		return (
			isset($this->attrs[$attr]) ? $this->attrs[$attr] : null
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

            return (string) (
        		(new DomWalker($render))
        			->preProcess()
        			->walk()
        			->postProcess()
        	);
        } catch (Exception $e) {
            return '<error>' . DomHelper::esc($e->getMessage()) . '</error>';
        }
	}
}
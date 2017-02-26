<?php
namespace Elementary;

use Exception;

/**
 * DOM elements factory
 *
 * @package  Elementary
 * @author   undercloud <lodashes@gmail.com>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     http://github.com/undercloud/elementary
 */
class Element extends AbstractDom
{
    /**
     * @var array
     */
	protected static $hooks = [
		'area', 'base', 'br', 'col', 'command', 'embed',
		'hr', 'img', 'input', 'keygen', 'link', 'meta',
		'param', 'source', 'track', 'wbr'
	];

    /**
     * @var boolean
     */
	protected $selfClose = false;

    /**
     * @var string
     */
	public $tagName;

    /**
     * @var array
     */
	public $attributes = [];

    /**
     * @var string
     */
	protected $content;

    /**
     * @param string $tagName tag name
     */
	public function __construct($tagName)
	{
		$this->tagName = $tagName;

		if (in_array($tagName, self::$hooks)) {
			$this->selfClose = true;
		}
	}

    /**
     * Check if attribute exists
     *
     * @param string $name of attribute
     *
     * @return boolean
     */
    public function hasAttribute($name)
    {
        return isset($this->attributes[$name]);
    }

    /**
     * Get attribute value
     *
     * @param string $name of attribute
     *
     * @return mixed
     */
    public function getAttribute($name)
    {
        if ('classList' == $name) {
            $name = 'class';
        }

        if (!isset($this->attributes[$name])) {
            if ('style' == $name) {
                $this->attributes[$name] = new Style;
            }

            if ('class' == $name) {
                $this->attributes[$name] = new ClassList;
            }
        }

        return (
            isset($this->attributes[$name]) ? $this->attributes[$name] : null
        );
    }

    /**
     * Remove attribute
     *
     * @param string $name of attribute
     *
     * @return boolean
     */
    public function removeAttribute($name)
    {
        if (isset($this->attributes[$name])) {
            unset($this->attributes[$name]);

            return true;
        }

        return false;
    }

    /**
     * Set attribute
     *
     * @param string $name key
     * @param mixed  $val  value
     *
     * @return null
     */
    public function setAttribute($name, $val)
    {
        if ('style' == $name) {
            $val = new Style($val);
        }

        if ('classList' == $name) {
            $name = 'class';
            $val = new ClassList($val);
        }

        $this->attributes[$name] = $val;
    }


    public function appendChild($element, $escape = false)
    {
        $element = (string) $element;
        if ($escape) {
            $element = Helper::esc($element);
        }

        $this->selfClose = true;
        $this->content .= $element;

        return $this;
    }

    public function prependChild($element, $escape = false)
    {
        $element = (string) $element;
        if ($escape) {
            $element = Helper::esc($element);
        }

        $this->selfClose = true;
        $this->content = $element . $this->content;

        return $this;
    }

    /**
     * Set content value
     *
     * @param string  $content value
     * @param boolean $escape  flag
     *
     * @return self
     */
    public function setContent($content, $escape = true)
    {
        $this->content = $escape ? Helper::esc($content) : $content;

        return $this;
    }

    /**
     * Get content value
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set self-close mode
     *
     * @param boolean $mode
     *
     * @return null
     */
	public function selfClose($mode)
	{
		$this->selfClose = $mode;
	}

    /**
     * Magic __set
     *
     * @param string $name key
     * @param mixed  $val  val
     *
     * @return null
     */
	public function __set($name, $val)
	{
        return $this->setAttribute($name, $val);
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
        return $this->getAttribute($name);
	}

    /**
     * Render element
     *
     * @return string
     */
	public function render()
	{
        $element = '<' . $this->tagName;
        if ($this->attributes) {
            $element .= ' ' . Helper::buildAttributes($this->attributes);
        }

        if ($this->selfClose) {
            $element .= ' />';
        } else {
            $element .= '>' . (string) $this->content . '</' . $this->tagName . '>';
        }

		return $element;
	}

    /**
     * Magic __toString
     *
     * @return string
     */
    public function __toString()
    {
        try {
            return $this->render();
        } catch (Exception $e) {
            return '<error>' . Helper::esc($e->getMessage()) . '</error>';
        }
    }
}
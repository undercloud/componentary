<?php
namespace Componentary;

use Exception;

/**
 * DOM elements factory
 *
 * @package Componentary
 * @author  undercloud <lodashes@gmail.com>
 * @license https://opensource.org/licenses/MIT MIT
 * @link    http://github.com/undercloud/componentary
 */
class Element extends AbstractDom
{
    use AttributesTrait;

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
    protected $tagName;

    /**
     * @var string
     */
    protected $content;

    /**
     * @param string $tagName tag name
     */
    public function __construct($tagName, array $attributes = [], $content = null)
    {
        $this->tagName = $tagName;

        if (in_array($tagName, self::$hooks)) {
            $this->selfClose = true;
        }

        $this->attributes = $attributes;
        $this->content = $content;
    }

    /**
     * Set tag name
     *
     * @param string $tagName tag name
     *
     * @return null
     */
    public function setTagName($tagName)
    {
        $this->tagName = $tagName;
    }

    /**
     * Get tag name
     *
     * @return string
     */
    public function getTagName()
    {
        return $this->tagName;
    }

    /**
     * Normalize attribute name
     *
     * @param string $name of attribute
     *
     * @return string
     */
    protected function normalizeAttribute($name)
    {
        if ('classList' === $name) {
            $name = 'class';
        }

        return $name;
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
        $name = $this->normalizeAttribute($name);

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
        $name = $this->normalizeAttribute($name);

        if (!isset($this->attributes[$name])) {
            if ('style' == $name) {
                $this->attributes[$name] = new Style;
            }

            if ('class' == $name) {
                $this->attributes[$name] = new ClassList;
            }
        }

        if (isset($this->attributes[$name])) {
            return $this->attributes[$name];
        }
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
        $name = $this->normalizeAttribute($name);

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
        $name = $this->normalizeAttribute($name);

        if ('style' === $name) {
            $val = (
                $val instanceof Componentary\Style
                ? $val
                : new Style($val)
            );
        }

        if ('class' === $name) {
            $val = (
                $val instanceof Componentary\ClassList
                ? $val
                : new ClassList($val)
            );
        }

        $this->attributes[$name] = $val;
    }

    /**
     * Append child content
     *
     * @param mixed   $element content
     * @param boolean $escape  flag
     *
     * @return self
     */
    public function appendChild($element, $escape = false)
    {
        $element = (string) $element;
        if ($escape) {
            $element = Utils::esc($element);
        }

        $this->selfClose = false;

        if (!is_array($this->content)) {
            $this->content = [$this->content];
        }

        $this->content[] = $element;

        return $this;
    }

    /**
     * Prepend child content
     *
     * @param mixed   $element content
     * @param boolean $escape  flag
     *
     * @return self
     */
    public function prependChild($element, $escape = false)
    {
        $element = (string) $element;
        if ($escape) {
            $element = Utils::esc($element);
        }

        $this->selfClose = false;

        if (!is_array($this->content)) {
            $this->content = [$this->content];
        }

        array_unshift($this->content, $element);

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
        $this->selfClose = false;
        $this->content = $escape ? Utils::esc((string) $content) : $content;

        return $this;
    }

    /**
     * Get content value
     *
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set self-close mode
     *
     * @param boolean $mode flag
     *
     * @return null
     */
    public function selfClose($mode)
    {
        $this->selfClose = $mode;
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
            $element .= ' ' . Utils::buildAttributes($this->attributes);
        }

        if ($this->selfClose) {
            $element .= ' />';
        } else {
            $element .= '>';

            if (is_array($this->content)) {
                $mapper = function ($item) {
                    return (string) $item;
                };

                $element = implode(array_map($mapper, $this->content));
            } else {
                $element .= (string) $this->content;
            }

            $element .= '</' . $this->tagName . '>';
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
            return '<error>' . Utils::esc($e->getMessage()) . '</error>';
        }
    }
}

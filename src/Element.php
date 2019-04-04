<?php
namespace Componentary;

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
     * @var array
     */
    protected $content = [];

    /**
     * @param string $tagName tag name
     * @param array  $attributes  map
     * @param mixed  $content     value
     *
     * @throws RenderException
     */
    public function __construct($tagName, array $attributes = [], $content = null)
    {
        $this->tagName = $tagName;

        if (in_array($tagName, self::$hooks)) {
            $this->selfClose = true;
        }

        foreach($attributes as $key => $value){
            $this->setAttribute($key, $value);
        }
        
        $this->content = $content;
    }

    /**
     * Set tag name
     *
     * @param string $tagName tag name
     *
     * @return void
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
     * Get attribute value
     *
     * @param string $name of attribute
     *
     * @throws RenderException
     *
     * @return mixed
     */
    public function getAttribute($name)
    {
        if (!isset($this->attributes[$name])) {
            if ('style' === $name) {
                $this->attributes[$name] = new Style;
            }

            if ('class' === $name) {
                $this->attributes[$name] = new ClassList;
            }
        }

        if (isset($this->attributes[$name])) {
            return $this->attributes[$name];
        }
    }

    /**
     * Set attribute
     *
     * @param string $name key
     * @param mixed  $val  value
     *
     * @throws RenderException
     *
     * @return void
     */
    public function setAttribute($name, $val)
    {
        if ('style' === $name) {
            $val = (
                $val instanceof Style
                ? $val
                : new Style($val)
            );
        }

        if ('class' === $name) {
            $val = (
                $val instanceof ClassList
                ? $val
                : new ClassList($val)
            );
        }

        $this->attributes[$name] = $val;
    }

    /**
     * Append child content
     *
     * @param AbstractDom $element content
     *
     * @return self
     */
    public function appendChild(AbstractDom $element)
    {
        $this->selfClose = false;
        $this->content[] = $element;

        return $this;
    }

    /**
     * Prepend child content
     *
     * @param AbstractDom $element content
     *
     * @return self
     */
    public function prependChild(AbstractDom $element)
    {
        $this->selfClose = false;
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
    public function appendContent($content, $escape = true)
    {
        $this->selfClose = false;
        $this->content[] = $escape ? Utils::esc((string) $content) : $content;

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
    public function prependContent($content, $escape = true)
    {
        $this->selfClose = false;
        array_unshift(
            $this->content,
            $escape ? Utils::esc((string) $content) : $content
        );

        return $this;
    }

    /**
     * Get content value
     *
     * @return array
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
     * @return void
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
            if ($this->content) {
                $mapper = function ($item) {
                    return (string) $item;
                };

                $element .= implode(array_map($mapper, $this->content));
            }

            $element .= '</' . $this->tagName . '>';
        }

        return $element;
    }
}

<?php
namespace Componentary;

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
     * @param array $attributes map
     */
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    /**
     * Set content value
     *
     * @param string  $content value
     *
     * @return self
     */
    public function setContent($content)
    {
        $this->content = $content;

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
}

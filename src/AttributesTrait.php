<?php
namespace Componentary;

/**
 * Common attributes helper
 *
 * @package Componentary
 * @author  undercloud <lodashes@gmail.com>
 * @license https://opensource.org/licenses/MIT MIT
 * @link    http://github.com/undercloud/componentary
 */
trait AttributesTrait
{
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
     * Set attribute
     *
     * @param string $name key
     * @param mixed  $val  value
     *
     * @return self
     */
    public function setAttribute($name, $val)
    {
        $this->attributes[$name] = $val;

        return $this;
    }

    /**
     * Set attributes map
     *
     * @param array $attributes map
     *
     * @return self
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = array_merge($this->attributes, $attributes);

        return $this;
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
        if (isset($this->attributes[$name])) {
            return $this->attributes[$name];
        }
    }

    /**
     * Get attributes map
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
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
}

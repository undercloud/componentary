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
}

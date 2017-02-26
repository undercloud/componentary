<?php
namespace Elementary;

/**
 * Abstract DOM entity
 *
 * @package  Elementary
 * @author   undercloud <lodashes@gmail.com>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     http://github.com/undercloud/elementary
 */
abstract class AbstractDom
{
    /**
     *  Abstract DOM declaration
     *
     * @return strind
     */
    abstract public function render();
}
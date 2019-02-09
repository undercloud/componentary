<?php
namespace Componentary;

use Exception;

/**
 * Abstract DOM entity
 *
 * @package Componentary
 * @author  undercloud <lodashes@gmail.com>
 * @license https://opensource.org/licenses/MIT MIT
 * @link    http://github.com/undercloud/componentary
 */
abstract class AbstractDom
{
    /**
     *  Abstract DOM declaration
     *
     * @return string
     */
    abstract public function render();

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

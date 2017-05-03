<?php
namespace Componentary;

use Exception;
use ReflectionClass;

/**
 * Componentary tag resolver
 *
 * @package Componentary
 * @author  undercloud <lodashes@gmail.com>
 * @license https://opensource.org/licenses/MIT MIT
 * @link    http://github.com/undercloud/componentary
 */
class Resolver
{
    /**
     * @var ReflectionClass
     */
    private $instance;

    /**
     * @param string $class name
     */
    public function __construct($class)
    {
        try {
            $this->instance = new ReflectionClass($class);
        } catch (Exception $e) {
            $prefix = Conponent::getPrefix();
            if ($prefix) {
                try {
                    $this->instance = new ReflectionClass($prefix .'\\' . $class);
                } catch (Exception $e) {
                    /* ... */
                }
            }
        }
    }

    /**
     * Check if component class is valid
     *
     * @return boolean
     */
    public function isValid()
    {
        return (
            null !== $this->instance
            and $this->instance->isSubclassOf(Component::class)
        );
    }

    /**
     * Generate tag content
     *
     * @param array $attrs list
     *
     * @return string
     */
    public function resolve(array $attrs = [])
    {
        return (string) $this->instance->newInstance()->setAttributes($attrs);
    }
}
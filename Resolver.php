<?php
namespace Elementary;

use Exception;
use ReflectionClass;

class Resolver
{
	private $instance;

	public function __construct($class)
	{
		try {
			$this->instance = new ReflectionClass($class);
		} catch (Exception $e) {
			/* ... */
		}
	}

	public function isValid()
	{
		return (
			null !== $this->instance 
			and $this->instance->isSubclassOf(Component::class)
		);
	}

	public function resolve(array $attrs = [])
	{
		return (
			$this
				->instance
				->newInstance()
				->set($attrs)
				->toString()
		);
	}
}
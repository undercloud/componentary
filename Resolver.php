<?php
//use ReflectionClass;

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
			and $this->instance->isSubclassOf('Element')
		);
	}

	public function resolve(array $attrs = array())
	{
		return (string) $this->instance->newInstance()->setAttrs($attrs)->toString();
	}
}
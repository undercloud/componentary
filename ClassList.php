<?php
namespace Elementary;

use Exception;

class ClassList
{
	/**
	 * @var array
	 */
	private $list = [];

	/**
	 * @param array|string $list of classes
	 */
	public function __construct($list = [])
	{
		if (!is_array($list) or !is_string($list)) {
			throw new Exception(sprintf(
                'Argument 1 must be array or string, %s given',
                gettype($list)
            ));
		}

		if (is_string($list)) {
			$list = explode(' ', $list);
		}

		$this->list = $list;
	}

	public function add($class)
	{
		if (!$this->has($class)) {
			$this->list[] = $class;
		}

		return $this;
	}

	public function has($class)
	{
		return in_array($class, $this->list, true);
	}

	public function remove($class)
	{
		$this->list = array_diff($this->list, (array) $class);

		return $this;
	}

	public function toggle($class)
	{
		if ($this->has($class)) {
			$this->remove($class);
		} else {
			$this->add($class);
		}

		return $this;
	}

	public function __toString()
	{
		return trim(implode(' ', $this->list));
	}
}
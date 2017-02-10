<?php
namespace Elementary;

class ClassList
{
	private $list = [];

	public function __construct($list = [])
	{
		if (!is_array($list)) {
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
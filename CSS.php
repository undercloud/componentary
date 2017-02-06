<?php
namespace Elementary;

class CSS
{
	private $map = [];

	private function normalizeKey($key)
	{
		$key = preg_replace_callback('~[A-Z]~', function ($match) {
			return '-' . strtolower($match[0]);
		}, $key);
	}

	public function has($key)
	{
		$key = $this->normalizeKey($key);

		return isset($this->map[$key]);
	}

	public function __set($key, $value)
	{
		$key = $this->normalizeKey($key);

		return $this->map[$key] = $value;
	}
	public function __get($key)
	{
		$key = $this->normalizeKey($key);

		return (isset($this->map[$key]) ? $this->map[$key] : null);
	}

	public function build()
	{
		$fn = function ($key, $value) {
			return $key . ':' . $value;
		};

		$pairs = array_map(
			$fn,
			array_keys($this->set),
			array_values($this->set)
		);

		return trim(implode(';', $pairs));
	}

	public function __toString()
	{
		return $this->build()
	}
}
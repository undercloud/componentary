<?php
namespace Elementary;

class Style
{
	private $map = [];

	public function __construct($style = [])
	{
		if (is_array($style)) {
			foreach ($style as $key => $val) {
				$key = $this->normalizeKey($key);
				$this->map[$key] = $val;
			}
		} else {
			$this->map = $this->parse($style);
		}
	}

	private function parse($style)
	{
		$pairs = array_filter(explode(';', $style));

		$map = [];
		foreach ($pairs as $pair) {
			list($key, $val) = explode(':', $pair, 2);
			$map[$key] = $val;
		}

		return $map;
	}

	private function normalizeKey($key)
	{
		return preg_replace_callback('~[A-Z]~', function ($match) {
			return '-' . strtolower($match[0]);
		}, $key);
	}

	public function has($key)
	{
		$key = $this->normalizeKey($key);

		return isset($this->map[$key]);
	}

	public function __set($key, $val)
	{
		$key = $this->normalizeKey($key);

		$this->map[$key] = $val;
	}
	public function __get($key)
	{
		$key = $this->normalizeKey($key);

		return (isset($this->map[$key]) ? $this->map[$key] : null);
	}

	public function build()
	{
		$fn = function ($key, $val) {
			return $key . ':' . $val;
		};

		$pairs = array_map(
			$fn,
			array_keys($this->map),
			array_values($this->map)
		);

		return trim(implode(';', $pairs));
	}

	public function __toString()
	{
		return $this->build();
	}
}
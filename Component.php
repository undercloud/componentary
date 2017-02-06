<?php
namespace Elementary;

use Exception;

class Component implements AbstractDom
{
	protected $attrs = [];

	public function setAttrs(array $attrs)
	{
		$this->attrs = array_merge($this->attrs, $attrs);

		return $this;
	}

	public function hasAttr($attr)
	{
		return isset($this->attrs[$attr]);
	}

	public function __set($key, $value)
	{
		$this->attrs[$key] = (string) $value;
	}

	public function render()
	{
		throw new Exception(';(');
	}

	public function toString()
	{
		$render = $this->render();
		$render = (new DomWalker($render))->walk();

		return $render;
	}

	public function __toString()
	{
		try {
			return $this->toString();
		} catch (Exception $e) {
			return '<error>' . DomHelper::esc($e->getMessage()) . '</error>';
		}
	}
}
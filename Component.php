<?php
namespace Elementary;

use Exception;

abstract class Component extends AbstractDom
{
	protected $attrs = [];

	public function set(array $attrs)
	{
		$this->attrs = array_merge($this->attrs, $attrs);

		return $this;
	}

	public function has($attr)
	{
		return isset($this->attrs[$attr]);
	}

	public function __set($attr, $val)
	{
		$this->attrs[$attr] = (string) $val;
	}

	public function __get($attr)
	{
		return (
			isset($this->attrs[$attr]) ? $this->attrs[$attr] : null
		);
	}

	public function toString()
	{
		$render = $this->render();

		return (string) (
			(new DomWalker($render))
				->preProcess()
				->walk()
				->postProcess()
		);
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
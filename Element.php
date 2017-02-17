<?php
namespace Elementary;

class Element extends AbstractDom
{
	protected static $hooks = [
		'area', 'base', 'br', 
		'col', 'command', 'embed', 
		'hr', 'img', 'input', 
		'keygen', 'link', 'meta', 
		'param', 'source', 'track', 'wbr'
	];

	protected $selfClose = false;

	protected $tag;

	protected $attrs = [];

	protected $content;

	public function __construct($tag)
	{
		$this->tag = $tag;

		if (in_array($tag, self::$hooks)) {
			$this->selfClose = true;
		}
	}

	public function selfClose($mode)
	{
		$this->selfClose = $mode;
	}

	public function __set($key, $val)
	{
		if ('style' == $key) {
			$val = new Style($val);
		}

		if ('class' == $key) {
			$val = new ClassList($val);	
		}

		$this->attrs[$key] = $val;
	}

	public function __get($key)
	{
		if (!isset($this->attrs[$key])) {
			if ('style' == $key) {
				$this->attrs[$key] = new Style;
			}

			if ('class' == $key) {
				$this->attrs[$key] = new ClassList;	
			}
		}

		return (
			isset($this->attrs[$key]) ? $this->attrs[$key] : null
		);
	}

	public function attr()
	{
		$args = func_get_args();

		switch (count($args)) {
			case 0:
				return $this->attrs;

			case 1:
				$key = $args[0];

				if (is_array($key)) {
					foreach ($key as $k => $v) {
						$this->__set($k, $v);
					}
				} else {
					return $this->__get($key);
				}
			break;

			case 2:
				list($key, $val) = $args;
				$this->__set($key, $val);
			break;
		}

		return $this;
	}

	public function content()
	{
		$args = func_get_args();

		switch (count($args)) {
			case 0:
				return $this->content;

			case 1:
				$this->content = DomHelper::esc($args[0]);
			break;

			case 2:
				list($content, $escape) = $args;

				$this->content = $escape ? DomHelper::esc($content) : $content;
			break;
		}

		return $this;
	}

	public function render()
	{
		return (
			'<' . $this->tag . 
			(
				$this->attrs
				? (' ' . DomHelper::buildAttributes($this->attrs))
				: ''
			) . 
			(
				$this->selfClose
				? ' />'
				: ('>' . (string) $this->content . '</' . $this->tag . '>')
			)
		);
	}

	public function __toString()
	{
		return $this->render();
	}
}
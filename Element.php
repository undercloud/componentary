<?php
namespace Elementary

class Element implements AbstractDom
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

	public function __construct($tag)
	{
		$this->tag = $tag;

		if (in_array($tag, self::$hooks)) {
			$this->selfClose = true;
		}
	}

	public function css()
	{
		
	}

	public function render()
	{
		if ($this->selfClose) {

		} else {

		}
	}

	public function addClass($class)
	{
		if (!$this->hasClass($class)) {
			
		} 
	}

	public function hasClass($name)
	{
		if (isset($this->attrs['class'])) {
			$class = explode(' ', $this->attrs['class']);

			return in_array($name, $class);
		}

		return false;
	}

	public function __toString()
	{
		return $this->render();
	}
}
<?php
class Element implements AbstractDom
{
	protected static $preprocessor;

	protected $tag;

	protected $content;

	protected $attrs = array();

	protected $selfClose = false;

	public function __construct($tag = null, $content = null, $attrs = array())
	{
		$this->setTag($tag);
		$this->setContent($content);
		$this->setAttrs($attrs);
	}

	public static function setPreprocessor(Closure $preprocessor)
	{
		$this->preprocessor = $preprocessor;
	}

	public function setSelfClose($flag)
	{
		$this->selfClose = $flag;

		return $this;
	}

	public function setTag($tag)
	{
		$this->tag = $tag;

		return $this;
	}

	public function setContent($content, $escape = false)
	{
		$this->content = $content;

		return $this;
	}

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
		if ($this->selfClose) {
			return '<' . $this->tag . ($this->attrs ? (' ' . DomHelper::buildArgs($this->attrs)) : '') . ' />';
		} else {
			return '<' . $this->tag . ($this->attrs ? (' ' . DomHelper::buildArgs($this->attrs)) : '') . '>' . $this->content . '</' . $this->tag . '>';
		}
	}

	public function toString()
	{
		$render = $this->render();
		if (self::$preprocessor instanceof Closure) {
			$render = call_user_func(self::$preprocessor, $render);
		}

		$render = DomHelper::assignComponents($render);

		return $render;
	}
}
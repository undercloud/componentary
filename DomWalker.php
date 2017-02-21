<?php
namespace Elementary;

use Closure;

class DomWalker
{
	private $render;
	private static $preprocessor;
	private static $postprocessor;

	public function __construct($render)
	{
		$this->render = $render;
	}

	public static function setPreprocessor(Closure $preprocessor = null)
	{
		self::$preprocessor = $preprocessor;
	}

	public static function getPreprocessor()
	{
		return self::$preprocessor;
	}

	public function preProcess()
	{
		if (self::$preprocessor instanceof Closure) {
			$this->render = call_user_func(self::$preprocessor, $this->render);
		}

		return $this;
	}

	public static function setPostprocessor(Closure $postprocessor = null)
	{
		self::$postprocessor = $postprocessor;
	}

	public static function getPostprocessor()
	{
		return self::$postprocessor;
	}

	public function postProcess()
	{
		if (self::$postprocessor instanceof Closure) {
			$this->render = call_user_func(self::$postprocessor, $this->render);
		}

		return $this;
	}

	public function walk()
	{
		$openBrace = false;
		$parsed = '';
		$tmp = '';
		foreach (str_split($this->render) as $symbol) {
			if ('<' === $symbol) {
				$openBrace = true;
			}

			if ($openBrace) {
				$tmp .= $symbol;
			} else {
				$parsed .= $symbol;
			}

			if ('>' === $symbol) {
				$openBrace = false;
				$parsed .= $this->assign($tmp);
				$tmp = '';
			}
		}

		$this->render = $parsed;

		return $this;
	}

	public function assign($tag)
	{
		if (ctype_upper($tag[1]) and '/>' === substr($tag, -2)) {
			$class = explode(' ', substr($tag, 1, -2), 2)[0];
			$class = str_replace('-', '\\', $class);

			$resolver = new Resolver($class);

			if ($resolver->isValid()) {
				$attrs = DomHelper::parseAttributes($tag);

				return $resolver->resolve($attrs);
			}
		}

		return $tag;
	}

	public function __toString()
	{
		return (string) $this->render;
	}
}
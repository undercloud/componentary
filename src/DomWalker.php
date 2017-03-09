<?php
namespace Componentary;

use Closure;

/**
 * Tag replacer
 *
 * @package  Componentary
 * @author   undercloud <lodashes@gmail.com>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     http://github.com/undercloud/componentary
 */
class DomWalker
{
    /**
     * @var string
     */
	private $render;

    /**
     * @var Closure|null
     */
	private static $preprocessor;

    /**
     * @var Closure|null
     */
    private static $postprocessor;

    /**
     * @param string $render content
     */
	public function __construct($render)
	{
		$this->render = $render;
	}

    /**
     * Set preprocess handle
     *
     * @param Closure|null $preprocessor instance
     */
	public static function setPreprocessor(Closure $preprocessor = null)
	{
		self::$preprocessor = $preprocessor;
	}

    /**
     * Get postprocess handle
     *
     * @return Closure|null
     */
	public static function getPreprocessor()
	{
		return self::$preprocessor;
	}

    /**
     * Run preprocess handler
     *
     * @return self
     */
	public function preProcess()
	{
		if (self::$preprocessor instanceof Closure) {
			$this->render = call_user_func(self::$preprocessor, $this->render);
		}

		return $this;
	}

    /**
     * Set postprocess handle
     *
     * @param Closure|null $postprocessor instance
     */
	public static function setPostprocessor(Closure $postprocessor = null)
	{
		self::$postprocessor = $postprocessor;
	}

    /**
     * Get postprocess handle
     *
     * @return Closure|null
     */
	public static function getPostprocessor()
	{
		return self::$postprocessor;
	}

    /**
     * Run postprocess handle
     *
     * @return self
     */
	public function postProcess()
	{
		if (self::$postprocessor instanceof Closure) {
			$this->render = call_user_func(self::$postprocessor, $this->render);
		}

		return $this;
	}

    /**
     * Walk and replace
     *
     * @return self
     */
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

    /**
     * Replace tag
     *
     * @param string $tag dom element
     *
     * @return string
     */
	public function assign($tag)
	{
		if (ctype_upper($tag[1]) and '/>' === substr($tag, -2)) {
			$class = explode(' ', substr($tag, 1, -2), 2)[0];
			$class = str_replace('-', '\\', $class);

			$resolver = new Resolver($class);

			if ($resolver->isValid()) {
				$attrs = Helper::parseAttributes($tag);

				return $resolver->resolve($attrs);
			}
		}

		return $tag;
	}

    /**
     * Magic __toString
     *
     * @return string
     */
	public function __toString()
	{
		return (string) $this->render;
	}
}
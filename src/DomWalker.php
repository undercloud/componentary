<?php
namespace Componentary;

use Closure;

/**
 * Tag resolver
 *
 * @package Componentary
 * @author  undercloud <lodashes@gmail.com>
 * @license https://opensource.org/licenses/MIT MIT
 * @link    http://github.com/undercloud/componentary
 */
class DomWalker
{
    /**
     * @var string
     */
    protected $render;

    /**
     * @var callable|null
     */
    protected static $preprocessor;

    /**
     * @var callable|null
     */
    protected static $postprocessor;

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
     * @param callable $preprocessor instance
     *
     * @return null
     */
    public static function setPreprocessor(callable $preprocessor)
    {
        self::$preprocessor = $preprocessor;
    }

    /**
     * Get postprocess handle
     *
     * @return callable|null
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
        if (null !== self::$preprocessor) {
            $this->render = call_user_func(self::$preprocessor, $this->render);
        }

        return $this;
    }

    /**
     * Set postprocess handle
     *
     * @param callable $postprocessor instance
     *
     * @return null
     */
    public static function setPostprocessor(callable $postprocessor)
    {
        self::$postprocessor = $postprocessor;
    }

    /**
     * Get postprocess handle
     *
     * @return callable|null
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
        if (null !== self::$postprocessor) {
            $this->render = call_user_func(self::$postprocessor, $this->render);
        }

        return $this;
    }

    /**
     * Walk and replace
     *
     * @return self
     */
    public function _walk()
    {
        $this->render = (new FiniteStateMachine($this->render))->walk();

        return $this;
    }

    public function walk()
    {
        $openBrace = false;
        $parsed = '';
        $tmp = '';
        //foreach (str_split($this->render) as $symbol) {
        $len = strlen($this->render);
        for ($i = 0; $i < $len; $i++ ) {
            $symbol = $this->render[$i];
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
            //if ($pure = $this->isIgnored($tag)) {
            //    return $pure;
            //}
            $class = explode(' ', substr($tag, 1, -2), 2)[0];
            $class = str_replace('-', '\\', $class);
            $resolver = new Resolver($class);
            if ($resolver->isValid()) {
                $attrs = Utils::parseAttributes($tag);
                return $resolver->resolve($attrs);
            }
        }
        return $tag;
    }

    /**
     * @ignore logic
     *
     * @param string $tag dom element
     *
     * @return string|false
     */
    public function isIgnored($tag)
    {
        if (false !== ($pos = strpos($tag, '@ignore'))) {
            return str_replace('@ignore', '', $tag);
        }

        return false;
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

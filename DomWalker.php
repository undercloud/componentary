<?php
namespace Elementary;

use Exception;
use DOMDocument;

class DomWalker
{
	private $render;

	public function __construct($render)
	{
		$this->render = $render;
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

		return $parsed;
	}

	public function parseAttributes($tag)
	{
		libxml_clear_errors();

		$document = new DOMDocument('1.0', 'UTF-8');
		if(!@$document->loadXML($tag)){
			$tag = debug_backtrace()[0]['args'][0];
			$lastError = libxml_get_last_error();

			throw new Exception($lastError->message . ' ' . $tag);
		}

		$attrs = [];
		foreach ($document->documentElement->attributes as $item) {
			$attrs[$item->name] = $item->value;
		}

		return $attrs;
	}

	public function assign($tag)
	{
		if (ctype_upper($tag[1]) and '/>' === substr($tag, -2)) {
			$class = reset(explode(' ', substr($tag, 1, -2), 2));

			$resolver = new Resolver($class);

			if ($resolver->isValid()) {
				$attrs = $this->parseAttributes($tag);

				return $resolver->resolve($attrs);
			}
		}

		return $tag;
	}
}
<?php
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
		$document = new DOMDocument('1.0', 'UTF-8');
		$document->loadXML($tag);

		$attrs = array();
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
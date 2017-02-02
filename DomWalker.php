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

	public function assign($tag)
	{
		if (ctype_upper($tag[1]) and '/>' === substr($tag, -2)) {
			$tag = substr($tag, 1, -2);
			$class = reset(explode(' ', $tag, 2));

			$resolver = new Resolver($class);

			if ($resolver->isValid()) {
				$attrs = [];

				return $resolver->resolve($attrs);
			}
		}

		return $tag;
	}
}
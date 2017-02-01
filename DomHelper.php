<?php
class DomHelper
{
	public static function esc($value)
	{
		return htmlentities($value, ENT_QUOTES, 'UTF-8', false);
	}

	public static function buildArgs(array $args)
	{
		$pairs = array();
		foreach ($args as $key => $value) {
			$pairs[] = $key . '=' . self::esc($value);
		}

		return implode(' ', $pairs);
	}

	public static function walk(DOMNodeList &$nodes)
	{
		foreach($nodes as $node){
			if($node->nodeType == XML_ELEMENT_NODE){
				if ($node->hasChildNodes()) {
					foreach ($node->childNodes as $child) {
						self::walk($child);
					}
				} else {
					return self::parse($node);
				}
			}
		}
	}

	public static function Ref($class, array $attrs)
	{
		try {
			$class = new ReflectionClass($class);

		} catch (Exception $e) {

		}
	}

	public static function parse(DOMNode &$element)
	{
		$inline = self::$d->saveXML($element);
		if (ctype_upper($inline[1]) and '/>' === substr($inline, -2)) {
			$inline = substr($inline, 1, -2);
			$class = reset(explode(' ', $inline, 2));

			$args = array_map(
				function ($attr) {
					return $attr->value;
				},
				iterator_to_array($element->attributes)
			);

			//var_dump($class,$args);
			$frag = self::$d->createDocumentFragment();
			$frag->appendXML('<div>Привет есть</div><img src="lal" /><hh>Ebele</hh>');

			$p = $element->parentNode;
			$p->replaceChild($frag,$element);
		}
	}

	public static $d;

	public static function assignComponents($render)
	{
		$render = '<?xml version="1.0" encoding="utf-8" ?><root>' . $render . '</root>';

		self::$d = new DOMDocument('1.0','UTF-8');
		self::$d->substituteEntities = false;
		self::$d->loadXML($render);

		self::walk(self::$d->documentElement->childNodes);

		$render = substr(self::$d->saveHTML(self::$d->documentElement), 6, -7);
	
		return $render;
	}
}
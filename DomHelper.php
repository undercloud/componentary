<?php
namespace Elementary;

use Exception;
use DOMDocument;

class DomHelper
{
	public static function esc($value)
	{
		return htmlentities($value, ENT_QUOTES, 'UTF-8', false);
	}

	public static function stringify($val)
	{
		if (is_bool($val)) {
			return $val ? 'true' : 'false';
		}

		if (is_array($val) or is_object($val)) {
			json_encode(null);

			$flag = (
				JSON_HEX_TAG | JSON_HEX_AMP |
				JSON_HEX_APOS | JSON_HEX_QUOT
			);
			$val = json_encode($val);

			if (json_last_error()) {
				throw new Exception(json_last_error_msg());
			}

			return $val;
		}

		return $val;
	}

	public static function buildAttributes(array $args)
	{
		$pairs = [];
		foreach ($args as $key => $val) {
			if (null !== $val and !is_resource($val)) {
				if (in_array($key, ['style', 'class'])) {
					$val = (string) $val;
				}

				$val = self::stringify($val);
				$pairs[] = $key . '="' . self::esc($val) . '"';
			}
		}

		return implode(' ', $pairs);
	}

	public static function parseAttributes($tag)
	{
		libxml_clear_errors();

		$document = new DOMDocument('1.0', 'UTF-8');
		if(!@$document->loadXML($tag)){
			$tag = debug_backtrace()[0]['args'][0];
			$lastError = libxml_get_last_error();

			throw new Exception($lastError->message . ' ' . $tag);
		}

		$map = [];

		$attrs = $document->documentElement->attributes;

		if ($init = $attrs->getNamedItem('_init_')) {
			$map = (array) json_decode($init->value, true);
		}

		foreach ($attrs as $item) {
			if ('_init_' === $item->name) {
				continue;
			}

			$map[$item->name] = $item->value;
		}

		return $map;
	}
}
<?php
namespace Elementary;

class DomHelper
{
	public static function esc($value)
	{
		return htmlentities($value, ENT_QUOTES, 'UTF-8', false);
	}

	public static function buildArgs(array $args)
	{
		$pairs = [];
		foreach ($args as $key => $value) {
			$pairs[] = $key . '=' . self::esc($value);
		}

		return implode(' ', $pairs);
	}
}
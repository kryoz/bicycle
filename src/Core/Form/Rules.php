<?php

namespace Core\Form;

use Core\Utils\UUID;

class Rules
{
	const LOWEST_YEAR = 1930;

	public static function notNull()
	{
		return function ($val) {
			return !is_null($val);
		};
	}

	public static function boolean()
	{
		return function ($val) {
			return $val === false || $val === true || $val === '0' || $val === '1';
		};
	}

	public static function integer()
	{
		return function ($val) {
			return is_integer($val);
		};
	}

	public static function email()
	{
		return function ($val) {
			return preg_match("~^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,4}$~uis", trim($val));
		};
	}

	public static function password()
	{
		return function ($val) {
			$len = mb_strlen(trim($val));
			return $len >= 8 && $len <= 20;
		};
	}

	public static function UUID()
	{
		return function ($val) {
			return UUID::check($val);
		};
	}

	public static function isArray()
	{
		return function ($val) {
			return is_array($val) && !empty($val);
		};
	}

	public static function string($length)
	{
		return function ($val) use ($length) {
			return is_string($val) && (mb_strlen($val) <= $length);
		};
	}

	public static function colorPattern()
	{
		return function ($val) {
			return preg_match("~^\#[0-9A-Z]{6}$~uis", trim($val));
		};
	}
}

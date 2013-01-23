<?php

namespace Core\Cache;

abstract class CacheFactory
{

	public static function get($cachetype = null)
	{
		if (!$cachetype) {
			if (function_exists('apc_cache_info'))
				$cachetype = 'Apc';
			else
				$cachetype = 'File';
		}

		$cacheClass = 'Core\Cache\Cache'.$cachetype;
		$cache = new $cacheClass();

		if (!($cache instanceof ICache)) {
			throw new \Exception(
			__CLASS__ . '::' . __FUNCTION__ .
			": $cacheClass is not instance of ICache"
			);
		}
		return $cache;
	}

}

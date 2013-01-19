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

		$cacheFile = dirname(__FILE__).DS.'Cache' . $cachetype . '.php';
		$cacheClass = 'Core\Cache\Cache' . $cachetype;

		if (file_exists($cacheFile)) {
			require_once $cacheFile;
			$cache = new $cacheClass();
		} else {
			throw new \Exception(
			__CLASS__ . '::' . __FUNCTION__ .
			": Cache type '$cachetype' not defined! Haven't found $cacheFile"
			);
		}

		if (!($cache instanceof ICache)) {
			throw new \Exception(
			__CLASS__ . '::' . __FUNCTION__ .
			": $cacheClass is not instance of ICache"
			);
		}
		return $cache;
	}

}

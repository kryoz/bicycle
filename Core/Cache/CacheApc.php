<?php

/**
 * APC cache handler
 */

namespace Core\Cache;

class CacheApc implements ICache
{

	public function __construct()
	{
		if (!function_exists('apc_cache_info'))
			throw new \Exception(__CLASS__.' class error: APC module does not exist');
	}

	public function has($scope)
	{
		return apc_exists($scope);
	}

	/**
	 * 
	 * @param string cell name corresponds to filename.txt
	 * @return mixed 
	 */
	public function get($scope)
	{
		return apc_fetch($scope);
	}

	/**
	 * 
	 * @param string $scope 
	 * @param mixed $data 
	 * @return boolean
	 */
	public function set($scope, $data = null, $ttl = null)
	{
		return apc_store($scope, $data, $ttl);
	}

	/**
	 * 
	 * @param string $scope
	 */
	public function flush($scope, $regular = false)
	{
		if ($regular) {
			foreach (new APCIterator('user', $scope) as $counter) {
				apc_delete($counter['key']);
			}
		}
		else
			return apc_delete($scope);
	}

}

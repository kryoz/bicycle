<?php

/**
 * Base cache handler
 *
 * @author kryoz
 */
namespace Core\Cache;

require_once 'CacheFactory.php';
require_once 'ICache.php';

class Cache implements ICache
{

	private $cache;
	protected static $instance;

	final function __construct($cachetype)
	{
		$this->cache = CacheFactory::get($cachetype);
	}

	final static function getInstance($cachetype = null)
	{
		if (empty(self::$instance)) {
			self::$instance = new Cache($cachetype);
		}
		
		return self::$instance;
	}

	/**
	 * Get data from cache
	 * @param string Cell name
	 * @return mixed Data from cache cell
	 */
	public function has($scope)
	{
		return $this->cache->has($scope);
	}

	/**
	 * Get data from cache
	 * @param string Cell name
	 * @return mixed Data from cache cell
	 */
	public function get($scope)
	{
		return $this->cache->has($scope) ? $this->cache->get($scope) : false;
	}

	/**
	 * Saves to cache cell data
	 * @param string $scope Cell name
	 * @param mixed $data Data to save
	 * @return boolean Success?
	 */
	public function set($scope, $data = null, $ttl = null)
	{
		$ttl = $ttl ? $ttl : CACHETTL;
		$this->cache->set($scope, $data, $ttl);
	}

	/**
	 * Erases cache cell
	 * @param string $scope cache cell name
	 * @param is regexp?
	 */
	public function flush($scope, $regular = false)
	{
		$this->cache->flush($scope, $regular);
	}

}
<?php

/**
 * Base cache handler
 *
 * @author kryoz
 */
namespace Core\Cache;

use Core\ServiceLocator\IService;

abstract class Cache implements IService
{
	protected static $instance;

	public function getServiceName()
	{
		return 'CACHE';
	}

	/**
	 * @return static
	 */
	final static function getInstance()
	{
		if (empty(self::$instance)) {
			self::$instance = new static();
		}
		
		return static::$instance;
	}

	abstract public function has($scope);
	abstract public function get($scope);
	abstract public function set($scope, $data = null, $ttl = null);
	abstract public function flush($scope, $regular = false);
}
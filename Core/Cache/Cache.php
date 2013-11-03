<?php

/**
 * Base cache handler
 *
 * @author kryoz
 */
namespace Core\Cache;

use Core\ServiceLocator\IService;
use Core\TSingleton;

abstract class Cache
{
    use TSingleton;

	abstract public function has($scope);
	abstract public function get($scope);
	abstract public function set($scope, $data = null, $ttl = null);
	abstract public function flush($scope, $regular = false);
}
<?php
namespace Core\ServiceLocator;

use Core\TSingleton;

class Locator
{
    use TSingleton;
	protected $registry;

	public static function add($name, $service)
	{
		$locator = self::getInstance();
		if (isset($locator->registry[$name])) {
			throw new ServiceAlreadyExistsException();
		}
		$locator->registry[$name] = $service;
		
		return $locator;
	}
	
	public static function get($serviceName)
	{
		$locator = self::getInstance();
		if (isset($locator->registry[$serviceName])) {
			return $locator->registry[$serviceName];
		} else {
			throw new ServiceNotFoundException();
		}
	}
}

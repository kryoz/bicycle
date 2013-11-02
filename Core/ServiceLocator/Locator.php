<?php
namespace Core\ServiceLocator;

use Core\TSingleton;

class Locator
{
    use TSingleton;
	protected $registry;

	public static function add(IService $service)
	{
		$locator = self::getInstance();
		if (isset($locator->registry[$service->getServiceName()])) {
			throw new ServiceAlreadyExistsException();
		}
		$locator->registry[$service->getServiceName()] = $service;
		
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

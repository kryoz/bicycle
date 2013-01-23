<?php
namespace Core\ServiceLocator;

class Locator
{
	private $registry;
	private static $instance;
	
	/**
	 * 
	 * @return Locator
	 */
	private static function getInstance()
	{
		if (empty(self::$instance)) {
			self::$instance = new static();
		}

		return self::$instance;
	}
	
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

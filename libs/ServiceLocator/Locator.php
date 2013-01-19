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
	final static function getInstance()
	{
		if (empty(self::$instance)) {
			self::$instance = new Locator();
		}

		return self::$instance;
	}
	
	public function add(IService $service)
	{
		if (isset($this->registry[$service->getServiceName()])) {
			throw new ServiceAlreadyExistsException();
		}
		$this->registry[$service->getServiceName()] = $service;
	}
	
	public function get($serviceName)
	{
		if (isset($this->registry[$serviceName])) {
			return $this->registry[$serviceName];
		} else {
			throw new ServiceNotFoundException();
		}
	}
}

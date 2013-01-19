<?php
namespace \Core\ServiceLocator;

interface IService
{
	static function getInstance();
	public function getServiceName();
}

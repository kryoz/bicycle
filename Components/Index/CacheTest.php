<?php
namespace Components\Index;

use Core\ServiceLocator\Locator;

class CacheTest
{
	public function generate()
	{
		for ($i = 0; $i < 20; $i++) {
			$data[] = rand(1, 1000);
		}

		Locator::get('cache')->set('index2', $data);

		return $data;
	}

	public function getCache()
	{
		return Locator::get('cache')->get('index2');
	}
}

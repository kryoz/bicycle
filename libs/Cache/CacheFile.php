<?php

/**
 * File cache handler
 */

namespace \Core\Cache;

class CacheFile implements ICache
{

	public function __construct()
	{
		if (!is_dir(CACHEDIR))
			throw new Exception(__CLASS__ . '::' . __FUNCTION__ . ': cache directory "' . CACHEDIR . '" does not exist!');
	}

	public function has($scope)
	{
		$filename = CACHEDIR . $scope . '.txt';

		return file_exists($filename);
	}

	/**
	 * 
	 * @param string cell name corresponds to filename.txt
	 * @return mixed 
	 */
	public function get($scope)
	{
		if (!$this->has($scope))
			return false;
		
		$filename = CACHEDIR . $scope . '.txt';
		$age = time() - @filemtime($filename);
		
		if (CACHETTL == 0 || (file_exists($filename) && $age >= CACHETTL)) {
			$this->flush($scope);
			return false;
		}

		list($data, $ttl) = unserialize(file_get_contents($filename));
		
		if ($age <= $ttl) {
			return $data;
		} else {
			$this->flush($scope);
			return false;
		}
	}

	/**
	 * 
	 * @param string $scope 
	 * @param mixed $data 
	 * @return boolean
	 */
	public function set($scope, $data = null, $ttl = null)
	{
		$filename = CACHEDIR . $scope . $ttl . '.txt';
		
		if ($ttl !== null) {
			$ttl = CACHETTL;
		}
		
		try {
			if ($data !== null) {
				$fh = @fopen($filename, 'w');
				if ($fh === false) {
					throw new Exception('Cache directory is not write enabled!');
				}
				
				$data = [$data, $ttl];
				
				fwrite($fh, serialize($data));
				fclose($fh);
			}
		} catch (Exception $e) {
			Debug::log(__CLASS__ . '::' . __FUNCTION__ . ': ' . $e->getMessage());
		}
	}

	/**
	 * 
	 * @param string $scope
	 */
	public function flush($scope, $regular = false)
	{
		if (file_exists(CACHEDIR . $scope . '.txt')) {
			unlink(CACHEDIR . $scope . '.txt');
		}
	}

}

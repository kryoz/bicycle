<?php

/**
 * File cache handler
 */

namespace Core\Cache;

class CacheFile implements ICache
{
	const EXT = '.cache';

	public function __construct()
	{
		if (!is_dir(CACHEDIR))
			throw new Exception(__CLASS__ . '::' . __FUNCTION__ . ': cache directory "' . CACHEDIR . '" does not exist!');
	}

	public function has($scope)
	{
		return file_exists($this->getCacheFile($scope));
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
		
		$filename = $this->getCacheFile($scope);
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
		$filename = $this->getCacheFile($scope);
		
		try {
			if ($data !== null) {
				$fh = @fopen($filename, 'w');
				if ($fh === false) {
					throw new Exception('Cache directory is not write enabled!');
				}
				
				$data = [$data, $ttl];
				
				fwrite($fh, serialize($data));
				fclose($fh);
				return true;
			}
		} catch (Exception $e) {
			Debug::log(__CLASS__ . '::' . __FUNCTION__ . ': ' . $e->getMessage());
			
			return false;
		}
	}

	/**
	 * 
	 * @param string $scope
	 */
	public function flush($scope, $regular = false)
	{
		// @TODO regexp search
		if (file_exists($this->getCacheFile($scope))) {
			unlink($this->getCacheFile($scope));
		}
	}
	
	private function getCacheFile($name)
	{
		return CACHEDIR . $name . self::EXT;
	}

}

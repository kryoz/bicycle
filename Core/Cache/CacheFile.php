<?php

/**
 * File cache handler
 */

namespace Core\Cache;

class CacheFile extends Cache
{
	const EXT = '.cache';

	public function __construct()
	{
		if (!is_dir(SETTINGS_CACHE_DIR)) {
			throw new \Exception('Cache directory "' . SETTINGS_CACHE_DIR . '" does not exist!');
        }
	}

	public function has($scope)
	{
		return file_exists($this->getCacheFile($scope));
	}

    /**
     * @param $scope
     * @return bool
     */
    public function get($scope)
	{
		if (!$this->has($scope))
			return false;
		
		$filename = $this->getCacheFile($scope);
		$age = time() - @filemtime($filename);
		
		if (SETTINGS_CACHE_TTL == 0 || (file_exists($filename) && $age >= SETTINGS_CACHE_TTL)) {
			$this->flush($scope);
			return false;
		}

		list($data, $ttl) = unserialize(file_get_contents($filename));
		
		if ($age <= $ttl) {
			return $data;
		}

        $this->flush($scope);
		return false;
	}


    /**
     * @param $scope
     * @param null $data
     * @param null $ttl
     * @return bool
     * @throws \Exception
     */
    public function set($scope, $data = null, $ttl = SETTINGS_CACHE_TTL)
	{
		$filename = $this->getCacheFile($scope);

        if ($data !== null) {
            $fh = @fopen($filename, 'w');
            if ($fh === false) {
                throw new \Exception('Cache directory is not write enabled!');
            }

            $data = [$data, $ttl];

            fwrite($fh, serialize($data));
            fclose($fh);
            return true;
        }
	}

    /**
     * @param $scope
     * @param bool $regular
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
		return SETTINGS_CACHE_DIR . $name . self::EXT;
	}

}

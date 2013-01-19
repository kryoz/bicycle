<?php
/**
 * Autoloader for shared classes
 */
namespace Core;

class Autoloader 
{
	const CP = 'AUTOLOADER_';
	private static $instance;
	
	private static function getInstance()
	{
		if (empty(self::$instance)) {
			self::$instance = new Autoloader();
		}

		return self::$instance;
	}
	
	private function getClassPath($className)
	{
		/* @var $cache \Core\Cache\Cache */
		$cache = \Core\Cache\Cache::getInstance();
		
		$filenames = $cache->get(CP.$className);
		
		if (!$filename) {
			$tree = explode('\\', $className);
			
			if (is_array($tree)) {
				$tree2 = $tree;

				if ($tree[0] == 'Core') {
					$tree[0] = LIBS;
					$tree2[0] = LIBS2;
				} elseif ($tree[0] == 'Components') {
					$tree[0] = COMPONENTS;
					$tree2[0] = COMPONENTS;
				}
				$filename = implode('/', $tree).'.php';
				$filename2 = implode('/', $tree2).'.php';
			} else {
				$filename = LIBS.$className.'.php';
				$filename2 = LIBS2.$className.'.php';
			}
			
			$filenames = [$filename, $filename2];
			$cache->set(CP.$className, $filenames);
		}
		
		return $filenames;
	}


	public static function classLoader($className) 
    {
        $autoLoader = self::getInstance();
		
		list($file, $file2) = $autoLoader->getClassPath($className);

        if (!file_exists($file) && !file_exists($file2)) {
                throw new \Exception(__CLASS__.": Tried to call <b>$className</b>, but none was found.
                    Also there's no <b>$file</b> or <b>$file2</b>");
        }

        if (file_exists($file2)) {
            require_once $file2;
    	}
    	else {
    		require_once $file;
    	}

        return true;
    }
    
    /**
     * Entry point of whole application
     */
    public static function register()
    {
        spl_autoload_register( ['Core\Autoloader', 'classLoader'] );
    }
}
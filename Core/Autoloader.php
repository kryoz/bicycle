<?php

/**
 * Autoloader for shared classes
 */

namespace Core;

require_once 'TSingleton.php';

class Autoloader
{
    use TSingleton;

    private function getClassPath($className)
    {
        $tree = explode('\\', $className);

        if (is_array($tree)) {
            $filename = implode('/', $tree) . '.php';
        } else {
            $filename = $className;
        }

        return SETTINGS_ROOT.$filename;
    }

    public static function classLoader($className)
    {
        $autoLoader = self::getInstance();

        $file = $autoLoader->getClassPath($className);

        if (!file_exists($file)) {
            throw new \Exception(__CLASS__ . ": Can't load <b>$className</b> class ($file)");
        }

        require_once $file;

        return true;
    }

    /**
     * Entry point of whole application
     */
    public static function register()
    {
        spl_autoload_register(array('\Core\Autoloader', 'classLoader'));
    }

}
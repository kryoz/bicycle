<?php

/**
 * Autoloader for shared classes
 */

namespace Core;

class Autoloader
{

    private static $instance;

    private static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    private function getClassPath($className)
    {
        $tree = explode('\\', $className);

        if (is_array($tree)) {
            $filename = implode('/', $tree) . '.php';
        } else {
            $filename = $className;
        }

        return $filename;
    }

    public static function classLoader($className)
    {

        $autoLoader = self::getInstance();

        $file = $autoLoader->getClassPath($className);

        if (!file_exists($file)) {
            throw new \Exception(__CLASS__ . ": Tried to call <b>$className</b>, but none was found.
					There's no <b>$file</b>");
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
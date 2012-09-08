<?php
/**
 * The entry of MVC framework
 * Please take a look in config.php before launching application
 * @author kubintsev
 */

require_once 'config.php';
require_once LIBS.'debug.php';
require_once LIBS.'functions.php'; // simple global functions

class App 
{
    /**
     * Autoloader for shared classes
     * @param string $class_name
     * @return boolean
     */
    public static function ClassLoader($class_name) 
    {
        $filename = strtolower($class_name) . '.php';
        $file = LIBS . $filename;

        if (file_exists($file) == false) {
                Debug::log('CLASSLOADER: Failed to get '.$file);
                return false;
        }
        require_once ($file);
        return true;
    }
    
    public static function Run()
    {
        spl_autoload_register( array('App', 'ClassLoader') );
        
        $router = new Router();
        $router->delegate();
    }
}

App::Run();



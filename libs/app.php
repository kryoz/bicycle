<?php

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
                throw new Exception('CLASSLOADER: Failed to get '.$file);
                return false;
        }
        require_once ($file);
        return true;
    }
    
    /**
     * Entry point of whole application
     */
    public static function Run()
    {
        spl_autoload_register( array('App', 'ClassLoader') );
        
        $router = new Router();
        $router->delegate();
    }
}
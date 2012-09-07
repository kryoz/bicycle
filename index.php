<?php
/**
 * Корневой файл парадигмы MVC
 * @author Кубинцев А.Н.
 */

require_once 'config.php';
require_once LIBS.'Debug.php';
require_once LIBS.'functions.php'; // глобальные бесклассовые функции

class App 
{
    // Метод автозагрузки классов
    public static function ClassLoader($class_name) 
    {
        $filename = $class_name . '.php';
        $file = LIBS . $filename;

        if (file_exists($file) == false) {
                Debug::log('CLASSLOADER: Failed to get '.$file);
                return false;
        }
        require_once ($file);
    }
    
    public static function Run()
    {
        // Регистрация метода автозагрузки классов
        spl_autoload_register( array('App', 'ClassLoader') );
        
        $router = new Router();
        $router->delegate();
    }
}

App::Run();



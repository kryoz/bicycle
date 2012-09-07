<?php
/**
 * �������� ���� ��������� MVC
 * @author �������� �.�.
 */

require_once 'config.php';
require_once LIBS.'Debug.php';
require_once LIBS.'functions.php'; // ���������� ������������ �������

class App 
{
    // ����� ������������ �������
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
        // ����������� ������ ������������ �������
        spl_autoload_register( array('App', 'ClassLoader') );
        
        $router = new Router();
        $router->delegate();
    }
}

App::Run();



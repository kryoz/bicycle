<?php

Class Router {

        private $path;
        private static $controller;
        private static $args;

        function __construct() {
            $this->setPath(COMPONENTS);
        }
        
        private function deSlash($str)
        {
            return trim($str, '/\\');
        }
        
        /**
         * Устанавливает каталог компонентов
         * @param string $path
         * @throws Exception
         */
        private function setPath($path) 
        {
            $this->path = $path;

            if (is_dir($path) == false) {
                    throw new Exception ('Invalid controller path: `' . $path . '`');
            }
            
            $this->path = $path;
        }
        
        /**
         * Метод обработки URL для маршрутизации
         * Возвращает имя контроллера и переданные ему параметры
         */
        private function getController() 
        {
            // Считывание запроса URL
            $route = (empty($_SERVER["REQUEST_URI"])) ? '' : $_SERVER["REQUEST_URI"];
            
            // Вычленяем префикс URL (корневую папку приложения)
            if ( $route )
                $route = substr( $route, strlen(URLROOT) );
            
            // Предотвращение дублирования главной страницы: редирект с index.php на /
            $mainpage = array('index.php', 'index', 'index/');
            
            if ( in_array($route, $mainpage) )
                $this->redirect();
            
            if (empty($route)) 
                $route = 'index';
            else
                $route = $this->deSlash($route); // Получаем раздельные части
            
            // Удаляем расширение файла и параметры из запроса, если кто-то додумался их передавать после "?"
            $route = preg_replace('#(\?.*)?$#', '', $route);
            
            
            /* Основная логика маршрутизатора 
             * Например отфильтрованный URL = 'index/ru/mow', 
             * тогда index - контроллер, ru и mow - параметры для контроллера
             */
            $parts = explode('/', $route);

            
            // 'index/ru/mow'
            if ( is_array($parts) )
            {
                $controller = array_shift($parts);
                $args = $parts;
            }
            else
                $controller = $route;
            
            self::$controller = $controller;
            self::$args = is_array($args) ? $args : array($args);
    }
   
    /*
     * Нахождение контроллера и передача управления ему
     */
    function delegate() 
    {
        $this->getController();

        $class = 'Controller_' . self::$controller;
        
        $controller_file = $this->path.DS.self::$controller.DS.self::$controller.'.php';

        if (is_readable($controller_file) == false) {
            if (DEBUG)
                throw new Exception('Controller "'.$controller_file.'" not found');
            else
                $this->redirect();
        }

        require_once ($controller_file);
        
        // Delegating control
        $controller = new $class(self::$controller);
        $action = self::$args[0];

        if (is_callable(array($class, $action)) ) {
            $controller->$action(self::$args);
        } else
            $controller->Run(self::$args);
    }

    function redirect($url = '')
    {
        header("HTTP/1.1 301 Moved Permanently");
        header('Location: http://'.$_SERVER['HTTP_HOST'].URLROOT.$url );
        exit();
    }
}

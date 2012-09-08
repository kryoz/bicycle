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
            $path = $this->deSlash($path);
            $path = DS.$path.DS;

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
            self::$args = $args;
    }
   
    /*
     * Нахождение контроллера и передача управления ему
     */
    function delegate() 
    {
        // Анализируем URL
        $this->getController();

        // Создаём экземпляр контроллера
        $class = 'Controller_' . self::$controller;
        
        $controller = $this->path.DS.self::$controller.DS.self::$controller.'.php';
        
        // Файл контроллера доступен?
        if (is_readable($controller) == false) {
            if (DEBUG)
                throw new Exception('Controller "'.$controller.'" not found');
            else
                $this->redirect();
        }
        
        // Подключаем контроллер
        require_once ($controller);
        
        // Делегируем управление контроллеру
        $obj = new $class(self::$controller);
        $obj->Run(self::$args);
    }

    function redirect($url = '')
    {
        header("HTTP/1.1 301 Moved Permanently");
        header('Location: http://'.$_SERVER['HTTP_HOST'].URLROOT.$url );
        exit();
    }
}

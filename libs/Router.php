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
         * ������������� ������� �����������
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
         * ����� ��������� URL ��� �������������
         * ���������� ��� ����������� � ���������� ��� ���������
         */
        private function getController() 
        {
            // ���������� ������� URL
            $route = (empty($_SERVER["REQUEST_URI"])) ? '' : $_SERVER["REQUEST_URI"];
            
            // ��������� ������� URL (�������� ����� ����������)
            if ( $route )
                $route = substr( $route, strlen(URLROOT) );
            
            // �������������� ������������ ������� ��������: �������� � index.php �� /
            $mainpage = array('index.php', 'index', 'index/');
            
            if ( in_array($route, $mainpage) )
                $this->redirect();
            
            if (empty($route)) 
                $route = 'index';
            else
                $route = $this->deSlash($route); // �������� ���������� �����
            
            // ������� ���������� ����� � ��������� �� �������, ���� ���-�� ��������� �� ���������� ����� "?"
            $route = preg_replace('#(\?.*)?$#', '', $route);
            
            
            /* �������� ������ �������������� 
             * �������� ��������������� URL = 'index/ru/mow', 
             * ����� index - ����������, ru � mow - ��������� ��� �����������
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
     * ���������� ����������� � �������� ���������� ���
     */
    function delegate() 
    {
        // ����������� URL
        $this->getController();

        // ������ ��������� �����������
        $class = 'Controller_' . self::$controller;
        
        $controller = $this->path.DS.self::$controller.DS.self::$controller.'.php';
        
        // ���� ����������� ��������?
        if (is_readable($controller) == false) {
            if (DEBUG)
                throw new Exception('Controller "'.$controller.'" not found');
            else
                $this->redirect();
        }
        
        // ���������� ����������
        require_once ($controller);
        
        // ���������� ���������� �����������
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

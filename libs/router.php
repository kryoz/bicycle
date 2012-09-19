<?php

Class Router {

        private $path;
        private static $controller;
        private static $args;
        private static $params;

        function __construct() {
            $this->setPath(COMPONENTS);
        }
        
        private function deSlash($str)
        {
            return trim($str, '/\\');
        }
        
        /**
         * Sets component path
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
         * Method to parse URL string
         * Returns controller name, path and parameters if has any
         */
        private function getController() 
        {
            $route = (empty($_SERVER["REQUEST_URI"])) ? '' : $_SERVER["REQUEST_URI"];
            
            // Cutting root url from url string
            if ( $route )
                $route = substr( $route, strlen(URLROOT) );
            
            // Avoiding duplicates
            $mainpage = array('index.php', INDEX, INDEX.'/', 'index.html');
            
            if ( in_array($route, $mainpage) )
                self::redirect();
            
            if (empty($route)) 
                $route = INDEX;
            else
                $route = $this->deSlash($route); 

            // Getting part from url after '?' and transforming it to array
            preg_match('#^(.*)\?(.*)$#', $route, $params);
            
            $params = explode('&', $params[2]);
            foreach ($params as $i=>$part)
            {
                $pair = explode('=', $part);
                unset($params[$i]);
                
                $params[$pair[0]] = $pair[1];
            }
            
            //Filtering virtual file extension
            $pattern = '#(\\'.VIRT_EXT.'\??.*)$#';
            $route = preg_replace($pattern, '', $route);
            
            
            /* Main router logic
             */
            $parts = explode('/', $route);

            
            if ( is_array($parts) )
            {
                if ($parts[0] == INDEX)
                {
                    array_shift($parts);
                    if (!empty($parts))
                        self::redirect( implode('/', $parts) );
                }
                
                $controller = array_shift($parts);
                $args = $parts;
            }
            else
                $controller = $route;
            
            self::$controller = $controller;
            self::$args = is_array($args) ? $args : array($args);
            self::$params = $params;
    }
   
    /*
     * Method to find controller and delegate the control to it
     */
    function delegate() 
    {
        $this->getController();

        $controller_file = $this->path.self::$controller.DS.self::$controller.'.php';

        if ( !is_readable($controller_file) ) 
        {
            $controller_file = $this->path.INDEX.DS.INDEX.'.php';
            array_unshift(self::$args, self::$controller);
            self::$controller = INDEX;
        }
        
        $class = 'Controller_' . self::$controller;

        require_once ($controller_file);
        
        // Delegating control
        $controller = new $class(self::$controller);

        if (is_callable(array($class, self::$args[0])) ) {
            $action = array_shift(self::$args);
            $controller->$action(self::$args, self::$params);
        } else
            $controller->index(self::$args, self::$params);
    }

    public static function redirect($url = '', $raw = false)
    {
        $address = $raw ? $url : 'http://'.$_SERVER['HTTP_HOST'].URLROOT.$url;
        header("HTTP/1.1 301 Moved Permanently");
        header('Location: '.$address);
        exit();
    }
}

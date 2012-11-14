<?php

Class Router {

    private static $path;
    private static $controller;
    private static $args;
    private static $params;

    function __construct() {
        $this->setPath(COMPONENTS);
    }

    private function deSlash($str) {
        return trim($str, '/\\');
    }

    /**
     * Sets component path
     * @param string $path
     * @throws Exception
     */
    private function setPath($path) {
        self::$path = $path;

        if (is_dir($path) == false)
            throw new Exception(__CLASS__.'::'.__FUNCTION__.': Invalid controller path `' . $path . '`');

        self::$path = $path;
    }

    /**
     * Method to parse URL string
     * Returns controller name, path and parameters if has any
     */
    private function getController() {
        $route = (empty($_SERVER["REQUEST_URI"])) ? '' : $_SERVER["REQUEST_URI"];

        // Cutting root url from url string
        if ($route)
            $route = substr($route, strlen(URLROOT));

        // Avoiding duplicates
        $mainpage = array(INDEX . '.php', INDEX, INDEX . '/', INDEX . VIRT_EXT);

        if (in_array($route, $mainpage))
            self::redirect();

        if (empty($route))
            $route = INDEX;
        else
            $route = $this->deSlash($route);

        // Getting part from url after '?' and transforming it to array
        $params = explode('?', $route);
        if (isset($params[1])) {
            $params = explode('&', $params[2]);
            foreach ($params as $i => $part) {
                $pair = explode('=', $part);
                unset($params[$i]);

                $params[$pair[0]] = $pair[1];
            }
        }

        //Cutting virtual file extension
        $pattern = '#(\\' . VIRT_EXT . ')$#';
        $route = preg_replace($pattern, '', $params[0]);

        //Filtering "-" by transforming it to "_"
        $route = preg_replace('#(\-)#', '_', $route);

        /* Main router logic */
        $parts = explode('/', $route);

        if (is_array($parts)) {
            if ($parts[0] == INDEX) {
                $controller = array_shift($parts);
                if (!empty($parts))
                    self::redirect(implode('/', $parts));
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

    private static function getControllerPath() {
        return self::$path . self::$controller . DS . self::$controller . '.php';
    }

    /*
     * Method to find controller and delegate the control to it
     */

    function delegate() {
        try {
            $this->getController();

            $controller_file = $this->getControllerPath();

            if (!is_readable($controller_file)) {
                $controller_file = self::$path . INDEX . DS . INDEX . '.php';
                if (self::$controller)
                    array_unshift(self::$args, self::$controller);
                self::$controller = INDEX;
            }

            require_once ($controller_file);

            // Delegating control
            $class = 'Controller_' . self::$controller;
            $controller = new $class();

            self::$args = !empty(self::$args) ? self::$args : array(INDEX);

            if (is_callable(array($class, self::$args[0]))) {
                $action = array_shift(self::$args);
                $controller->$action(self::$args, self::$params);
            }
            else
                $controller->index(self::$args, self::$params); // this case is required for complex controllers
        } catch (Exception $e) {
            Debug::log(__CLASS__.'::'.__FUNCTION__.': ', $e->getMessage());
        }
    }

    public static function redirect($url = '', $raw = false) {
        $address = $raw ? $url : PROTOCOL.'://' . $_SERVER['HTTP_HOST'] . URLROOT . $url;
        header(PROTOCOL . " 301 Moved Permanently");
        header('Location: ' . $address);
        exit();
    }

    public static function NoPage() {
        header( PROTOCOL . " 404 Not Found");

        self::$controller = 'error404';

        require_once (self::getControllerPath());

        $class = 'Controller_' . self::$controller;

        $controller = new $class(self::$controller);
        $controller->index(self::$args, self::$params);
        exit();
    }

}

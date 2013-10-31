<?php

namespace Site;

use Components\Error404\Error404;


/**
 * Number one for serious refactoring. Shame for me :( Hope to do soon
 * @package Site
 */
class Router
{

    const CONTROLLER = 'c';
    const PAGE = 'p';

    private static $path;
    private static $controller;
    private static $page;
    private static $args;
    private static $params;

    public function __construct()
    {
        $this->setPath(SETTINGS_COMPONENTS_DIR);
    }

    /**
     * Sets component path
     * @param string $path
     * @throws \Exception
     */
    private function setPath($path)
    {
        self::$path = $path;

        if (is_dir($path) == false)
            throw new \Exception(__CLASS__ . '::' . __FUNCTION__ . ': Invalid controller path `' . $path . '`');

        self::$path = $path;
    }

    /**
     * Method to parse URL string
     * Returns controller name, path and parameters if has any
     */
    private function getController()
    {
        $route = (empty($_SERVER["REQUEST_URI"])) ? '' : $_SERVER["REQUEST_URI"];

        // Cutting root url from url string
        if ($route)
            $route = substr($route, strlen(URLROOT));

        // Avoiding duplicates
        $mainpage = [
            INDEX . '.php',
            INDEX,
            INDEX . '/',
            INDEX . VIRT_EXT
        ];

        if (in_array($route, $mainpage))
            self::redirect();

        if (empty($route))
            $route = INDEX;
        else
            $route = trim($route, '/\\');

        // Getting part from url after '?' and transforming it to array
        $params = explode('?', $route);
        if (count($params) > 1) {
            $query = explode('&', $params[1]);
            if (is_array($query)) {
                foreach ($query as $i => $part) {
                    $pair = explode('=', $part);
                    unset($query[$i]);

                    $query[$pair[0]] = $pair[1];
                }
            }
            if (SEFENABLED) {
                $route = $params[0];
            } else {
                $route = $query;
            }
        }

        if (SEFENABLED) {
            //Filtering "-" by transforming it to "_"
            $route = preg_replace('#(\-)#', '_', $route);

            //Cutting virtual file extension
            $pattern = '#(\\' . VIRT_EXT . ')$#';
            $route = preg_replace($pattern, '', $route);

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
            } else
                $controller = $route;


            self::$controller = $controller;
            self::$args = is_array($args) ? $args : [$args];
            self::$page = isset(self::$args[0]) ? array_shift(self::$args) : INDEX;
            self::$params = $params;

            // Case of hidden controller and assuming INDEX
            $controllerClass = $this->getControllerPath();
            if (!is_callable($controllerClass)) {
                // assume "controller" is a page
                if (self::$controller) {
                    self::$page = self::$controller;
                }
                self::$controller = INDEX;
            }
        } else {
            if (isset($route[self::CONTROLLER])) {
                self::$controller = $route[self::CONTROLLER];
                unset($route[self::CONTROLLER]);
            } else {
                self::$controller = INDEX;
            }

            if (isset($route[self::PAGE])) {
                self::$page = $route[self::PAGE];
                unset($route[self::PAGE]);
            } else {
                self::$page = INDEX;
            }

            self::$args = $route;
            self::$params = count($params) > 1 ? $params[1] : $params;
        }
    }

    private static function getControllerPath()
    {
        return '\Components\\' . ucfirst(self::$controller) . '\\' . ucfirst(self::$controller);
    }

    /*
     * Method to find controller and delegate the control to it
     */

    public function delegate()
    {
        $this->getController();

        // Delegating control
        $class = $this->getControllerPath();
        $controller = new $class();

        if (is_callable([$class, self::$page])) {
            $controller->{self::$page}(self::$args, self::$params);
        } //this case is required for complex controllers
        elseif (isset($controller->complex)) {
            $controller->index(self::$args, self::$params);
        } else {
            self::NoPage();
        }
    }

    public static function redirect($url = '', $raw = false)
    {
        $address = $raw ? $url : PROTOCOL . '://' . $_SERVER['HTTP_HOST'] . URLROOT . $url;
        header("HTTP/1.1 301 Moved Permanently");
        header('Location: ' . $address);
        exit();
    }

    public static function NoPage()
    {
        header("HTTP/1.1 404 Not Found");

        self::$controller = '\Components\Error404\Error404';

        $controller = new Error404();
        $controller->index(self::$args, self::$params);
        exit();
    }

}
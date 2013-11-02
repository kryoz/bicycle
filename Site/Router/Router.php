<?php

namespace Site\Router;

use Core\HttpRequest;


class Router
{
    protected $controllerFinder;

    public function __construct(RouterStrategy $controllerFinder)
    {
        $this->controllerFinder = $controllerFinder;
    }

    public function delegateControl()
    {
        $request = new HttpRequest();
        try {
            $controllerClass = $this->controllerFinder->getControllerClass($request);
        } catch(RouteNotFoundException $e) {
            self::NoPage();
            return;
        }

        $controller = new $controllerClass();
        $controller->handleRequest($request);
    }

    public static function redirect($url = '', $raw = false)
    {
        $address = $raw ? $url : SETTINGS_PROTOCOL . '://' . $_SERVER['HTTP_HOST'] . SETTINGS_URLROOT . $url;
        header("HTTP/1.1 301 Moved Permanently");
        header('Location: ' . $address);
    }

    public static function NoPage()
    {
        header("HTTP/1.1 404 Not Found");

        $request = new HttpRequest();
        /** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
        $controller = new \Components\Error404\Index();
        $controller->handleRequest($request);
    }
}

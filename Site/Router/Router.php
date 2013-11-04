<?php

namespace Site\Router;

use Core\HttpRequest;
use Site\BaseController;


class Router
{
    protected $controllerFinder;

    protected $controllerMap = [
        'index' => 'Components\Index\Index',
        'test' => 'Components\Test\Index'
    ];

    public function __construct(RouterStrategy $controllerFinder)
    {
        $this->controllerFinder = $controllerFinder;
    }

    public function delegateControl(HttpRequest $request)
    {
        try {
            $controllerClass = $this->controllerFinder->getControllerClass($request);
            $controllerAction = $this->controllerFinder->getControllerAction($request);

            $controller = new $controllerClass();
            /* @var $controller BaseController */

            if (!isset($this->controllerMap[$controllerAction])) {
                throw new RouteNotFoundException;
            }

            $controller->{$this->controllerMap[$controllerAction]}($request);
        } catch(RouteNotFoundException $e) {
            self::NoPage();
            return;
        }
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
        $controller->defaultAction($request);
    }
}

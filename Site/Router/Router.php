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
            $controllerClass = $this->getControllerClass($request);
            $controller = new $controllerClass();
            /* @var $controller BaseController */

            $controllerAction = $this->getControllerAction($request, $controller);

            $controller->{$controllerAction}($request);
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

    public static function NoAccess()
    {
        header("HTTP/1.1 403 Forbidden");

        $request = new HttpRequest();
        /** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
        $controller = new \Components\Error403\Index();
        $controller->defaultAction($request);
    }

    /**
     * @param HttpRequest $request
     * @return string
     * @throws RouteNotFoundException
     */
    private function getControllerClass(HttpRequest $request)
    {
        $controller = $this->controllerFinder->getControllerClass($request);

        if (!isset($this->controllerMap[$controller])) {
            throw new RouteNotFoundException;
        }

        $controllerClass = $this->controllerMap[$controller];
        return $controllerClass;
    }

    /**
     * @param HttpRequest $request
     * @param BaseController $controller
     * @return string
     * @throws RouteNotFoundException
     */
    private function getControllerAction(HttpRequest $request, BaseController $controller)
    {
        $controllerAction = $this->controllerFinder->getControllerAction($request);
        if (!isset($controller->getActionMap()[$controllerAction])) {
            throw new RouteNotFoundException;
        }
        return $controller->getActionMap()[$controllerAction];
    }
}

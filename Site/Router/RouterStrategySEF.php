<?php
/**
 * Created by PhpStorm.
 * User: kryoz
 * Date: 02.11.13
 * Time: 23:45
 */

namespace Site\Router;

use Core\HttpRequest;

class RouterStrategySEF extends RouterStrategy
{
    protected $indexAliases = [
        'index.php',
        'index',
        'index/',
        'index.html'
    ];

    public function getControllerClass(HttpRequest $request)
    {
        $requestUrl = $request->getRequest();

        if ($requestUrl) {
            $requestUrl = substr($requestUrl, strlen(SETTINGS_URLROOT));
        }

        if (in_array($requestUrl, $this->indexAliases)) {
            Router::redirect();
            exit();
        }

        $requestUrl = $this->extractPathString($requestUrl);

        $parts = explode('/', $requestUrl);
        $page = 'defaultAction';

        if (is_array($parts)) {
            $requestUrl = $parts[0];
            $page = $parts[1];
        }

        if (in_array($requestUrl, $this->controllerMap)) {
            self::$page = $page;
            return $this->controllerMap[$requestUrl];
        }

        throw new RouteNotFoundException;
    }

    /**
     * @param $requestUrl
     * @return array
     */
    private function extractPathString($requestUrl)
    {
        if (empty($requestUrl)) {
            $requestUrl = 'index';
        } else {
            $requestUrl = trim($requestUrl, '/\\');
        }

        $params = explode('?', $requestUrl);
        if (is_array($params)) {
            $requestUrl = $params[0];
        }

        $requestUrl = preg_replace('~(\\\.html)$~ui', '', $requestUrl);

        return $requestUrl;
    }
} 
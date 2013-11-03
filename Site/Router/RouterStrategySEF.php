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

    protected $pathParts;

    public function getControllerClass(HttpRequest $request)
    {
        $requestUrl = $request->getRequestUrl();

        if ($requestUrl) {
            $requestUrl = substr($requestUrl, strlen(SETTINGS_URLROOT));
        }

        if (in_array($requestUrl, $this->indexAliases)) {
            Router::redirect();
            exit();
        }

        $requestUrl = $this->extractPathString($requestUrl);

        $this->$pathParts = explode('/', $requestUrl);
        $requestUrl = $this->$pathParts[0];

        if (isset($requestUrl, $this->controllerMap)) {
            return $this->controllerMap[$requestUrl];
        }

        throw new RouteNotFoundException;
    }

    public function getControllerAction(HttpRequest $request)
    {
        return isset($this->pathParts[1]) ? $this->pathParts[1] : 'defaultAction';
    }

    /**
     * @param $requestUrl
     * @return array
     */
    private function extractPathString($requestUrl)
    {
        if (empty($requestUrl) || $requestUrl === '/') {
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
<?php
/**
 * Created by PhpStorm.
 * User: kryoz
 * Date: 02.11.13
 * Time: 23:45
 */

namespace Site\Router;

use Core\HttpRequest;

class RouterStrategyRaw extends RouterStrategy
{
    public function getControllerClass(HttpRequest $request)
    {
        return isset($request->getGet()['c']) ? strtolower($request->getGet()['c']) : 'index';
    }

    public function getControllerAction(HttpRequest $request)
    {
        return isset($request->getGet()['p']) ? strtolower($request->getGet()['p']) : 'defaultAction';
    }
}
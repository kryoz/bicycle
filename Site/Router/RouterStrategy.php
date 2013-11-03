<?php
/**
 * Created by PhpStorm.
 * User: kryoz
 * Date: 02.11.13
 * Time: 23:39
 */

namespace Site\Router;


use Core\HttpRequest;

abstract class RouterStrategy
{
    protected $controllerMap = [
        'index' => 'Components\Index\Index',
        'test' => 'Components\Test\Index'
    ];

    abstract public function getControllerClass(HttpRequest $request);
    abstract public function getControllerAction(HttpRequest $request);
}
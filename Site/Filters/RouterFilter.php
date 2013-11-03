<?php
/**
 * Created by PhpStorm.
 * User: kryoz
 * Date: 03.11.13
 * Time: 15:19
 */

namespace Site\Filters;

use Core\Chain\ChainInterface;
use Site\Router\Router;
use Site\Router\RouterStrategyRaw;

class RouterFilter implements ChainInterface
{
    public function handleRequest($request)
    {
        $router = new Router(new RouterStrategyRaw()); // Site\Router\RouterStrategySEF for SEF mode
        $router->delegateControl($request);
    }

} 
<?php
/**
 * Created by PhpStorm.
 * User: kryoz
 * Date: 02.11.13
 * Time: 14:21
 */

namespace Core;


use Core\ServiceLocator\IService;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;


class ErrorHandler
{
    use TSingleton;

    public function __construct()
    {
        $run = new Run;
        $handler = new PrettyPageHandler;
        $run->pushHandler($handler);
        $run->register();

        return $run;
    }
}
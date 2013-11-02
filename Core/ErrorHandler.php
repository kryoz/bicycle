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


class ErrorHandler implements IService
{
    use TSingleton;

    public function getServiceName()
    {
        return 'errorHandler';
    }

    public function __construct()
    {
        $run = new Run;
        $handler = new PrettyPageHandler;
        $run->pushHandler($handler);

        $run->pushHandler(function ($exception, $inspector, $run) {
                $frames = $inspector->getFrames();
                foreach ($frames as $i => $frame) {
                    $frame->addComment('This is frame number ' . $i, 'example');

                    if ($function = $frame->getFunction()) {
                        $frame->addComment("This frame is within function '$function'", 'cpt-obvious');
                    }
                }
            }
        );

        $run->register();

        return $run;
    }
}
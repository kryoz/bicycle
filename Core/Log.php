<?php
/**
 * Created by PhpStorm.
 * User: kryoz
 * Date: 02.11.13
 * Time: 14:21
 */

namespace Core;


use Core\ServiceLocator\IService;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class Log extends Logger implements IService
{
    use TSingleton;

    public function getServiceName()
    {
        return 'logger';
    }

    public function __construct()
    {
        parent::__construct('BicycleLogger', [new StreamHandler(SETTINGS_ROOT.DS.'logs/debug.log', Logger::DEBUG)]);
    }
}
<?php
/**
 * The entry of MVC framework
 * Please take a look in bootstrap.php before launching application
 * @author kryoz
 */

namespace Site;

use Core\Cache\CacheFile;
use Core\DB;
use Core\ServiceLocator\Locator;
use Site\Router\Router;
use Site\Router\RouterStrategyRaw;

require_once 'bootstrap.php';
Locator::add(CacheFile::getInstance()); // you can try CacheAPC if you have php APC extension
Locator::add(DB::getInstance());

try {
    $router = new Router(new RouterStrategyRaw()); // Site\Router\RouterStrategySEF for SEF mode
    $router->delegateControl();
} catch (\Exception $e) {
    if (SETTINGS_IS_DEBUG) {
        throw $e; // 'Whoops' will intercept it
    }
    Locator::get('logger')
        ->warn($e->getMessage()."\n".$e->getTraceAsString(), ['Uncaught Exception']);
}
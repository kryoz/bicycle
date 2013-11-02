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

require_once 'bootstrap.php';
Locator::add(CacheFile::getInstance()); // you can try CacheAPC if you have php APC extension
Locator::add(DB::getInstance());

try {
    $router = new Router();
    $router->delegate();
} catch (\Exception $e) {
    Locator::get('logger')->warn($e->getMessage()."\n".$e->getTraceAsString(), ['Uncaught Exception']);
}
<?php
/**
 * The entry of MVC framework
 * Please take a look in bootstrap.php before launching application
 * @author kryoz
 */

namespace Site;

use Core\Chain\ChainContainer;
use Core\HttpRequest;
use Core\ServiceLocator\Locator;
use Site\Filters\RouterFilter;

require_once 'bootstrap.php';

$chain = new ChainContainer();
try {
    $chain
        ->setRequest(new HttpRequest())
        ->addHandler(new RouterFilter())
        ->run();
} catch (\Exception $e) {
    if (SETTINGS_IS_DEBUG) {
        throw $e; // 'Whoops' will intercept it
    }
    Locator::get('logger')
        ->warn($e->getMessage()."\n".$e->getTraceAsString(), ['Uncaught Exception']);
}
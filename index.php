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
use Site\Filters\SessionFilter;

require_once 'bootstrap.php';

$app = new ChainContainer();

try {
    $app
        ->setRequest(new HttpRequest())
        ->addHandler(new SessionFilter())
        ->addHandler(new RouterFilter());

    $app->run();
} catch (\Exception $e) {
    if (SETTINGS_IS_DEBUG) {
        throw $e; // 'Whoops' will intercept it
    }
    Locator::get('logger')
        ->warn($e->getMessage()."\n".$e->getTraceAsString(), ['Uncaught Exception']);
}
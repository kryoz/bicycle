<?php
/**
 * The entry of MVC framework
 * Please take a look in bootstrap.php before launching application
 * @author kryoz
 */

require_once 'bootstrap.php';

$router = new Router();
$router->delegate();
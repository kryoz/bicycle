<?php
/**
 * The entry of MVC framework
 * Please take a look in config.php before launching application
 * @author kubintsev
 */

require_once 'config.php';
require_once LIBS.'functions.php'; // simple global functions
require_once LIBS.'autoloader.php'; // application class

Autoloader::Register();
$router = new Router();
$router->delegate();
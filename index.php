<?php
/**
 * The entry of MVC framework
 * Please take a look in config.php before launching application
 * @author kubintsev
 */

require_once 'config.php';
require_once LIBS.'functions.php'; // simple global functions
require_once LIBS.'app.php'; // application class

App::Run();



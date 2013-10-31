<?php
/**
 * The entry of MVC framework
 * Please take a look in bootstrap.php before launching application
 * @author kryoz
 */

namespace Site;

use Core\Debug;

try {
	require_once 'bootstrap.php';
	$router = new Router();
	$router->delegate();
} catch (\Exception $e) {
	Debug::log($e);
	if (SETTINGS_IS_DEBUG) {
		echo Debug::getlog();
	}
}

<?php

use Core\Autoloader;
use Core\ErrorHandler;
use Core\Log;
use Core\ServiceLocator\Locator;
use Site\SapeClient;

define ('SETTINGS_IS_DEBUG', true); //dev mode on

define ('DS', DIRECTORY_SEPARATOR);
define ('SETTINGS_DOCROOT', $_SERVER['DOCUMENT_ROOT'] . DS); // root directory of the site
define ('SETTINGS_ROOT', dirname(__FILE__) . DS); // filesystem root of framework
define ('SETTINGS_URLROOT', ''); // URL root of framework

define ('SETTINGS_PROTOCOL', 'http'); // HTTP or HTTPS
define ('SETTINGS_SEFENABLED', false); // SEF-url routes

define ('SETTINGS_COMPONENTS_DIR', SETTINGS_DOCROOT . 'Components' . DS); // path to your applications
define ('SETTINGS_LIBS', SETTINGS_ROOT . 'Core'); // path to shared libraries
define ('SETTINGS_LIBS2', SETTINGS_ROOT . 'Site'); // site specific libs
define ('SETTINGS_GLOBALVIEWS_DIR', SETTINGS_DOCROOT . 'tmpl' . DS); // path to global templates

define ('SETTINGS_CACHE_DIR', DS . 'tmp' . DS);
define ('SETTINGS_CACHE_TTL', 3600); // cache time to live. '0' to disable
define ('CP', md5(SETTINGS_DOCROOT) . '_'); // cache prefix

define ('SETTINGS_CODEPAGE', 'utf-8'); // HTML codepage
define ('SETTINGS_INNERCODEPAGE', 'utf8'); // inner codepage

/* DB connection settings */
define ('SETTINGS_DB_SCHEME', 'mysql');
define ('SETTINGS_DB_ADDRESS', 'dbname=test;host=localhost');
define ('SETTINGS_DB_USER', 'test');
define ('SETTINGS_DB_PASS', '123');

define ('SETTINGS_SAPE_USER', ''); // your SAPE id if you use sape.ru

if (SETTINGS_IS_DEBUG) {
    error_reporting(E_ALL ^ E_NOTICE);
    ini_set('display_errors', true);
}

ini_set('max_execution_time', 120);
setlocale(LC_ALL, 'ru_RU.' . SETTINGS_INNERCODEPAGE);
mb_internal_encoding(SETTINGS_INNERCODEPAGE);
date_default_timezone_set('Europe/Moscow');

require_once SETTINGS_LIBS.DS."Autoloader.php";
Autoloader::register();

require_once  SETTINGS_ROOT.DS."vendor/autoload.php";

Locator::add(Log::getInstance());
Locator::add(ErrorHandler::getInstance());

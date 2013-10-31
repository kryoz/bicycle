<?php

use Core\Autoloader;
use Core\Cache\CacheFile;
use Core\DB;
use Core\ServiceLocator\Locator;
use Site\SapeClient;

define ('SETTINGS_IS_DEBUG', true); //dev mode on

define ('DS', DIRECTORY_SEPARATOR);
define ('DOCROOT', $_SERVER['DOCUMENT_ROOT'] . DS); // root directory of the site
define ('ROOT', dirname(__FILE__) . DS); // filesystem root of framework
define ('URLROOT', ''); // URL root of framework

define ('PROTOCOL', 'http'); // HTTP or HTTPS
define ('SEFENABLED', false); // SEF-url routes

define ('SETTINGS_COMPONENTS_DIR', DOCROOT . 'Components' . DS); // path to your applications
define ('LIBS', ROOT . 'Core'); // path to shared libraries
define ('LIBS2', ROOT . 'Site'); // site specific libs
define ('SETTINGS_GLOBALVIEWS_DIR', DOCROOT . 'tmpl' . DS); // path to global templates
define ('LOGFILE', DOCROOT . 'debug.log'); // make sure that file has write-enable rights

define ('CACHEDIR', DS . 'tmp' . DS);
define ('CACHETTL', 3600); // cache time to live. '0' to disable
define ('CP', md5(DOCROOT) . '_');

define ('CODEPAGE', 'utf-8'); // HTML codepage
define ('INNERCODEPAGE', 'utf8'); // inner codepage

define ('VIRT_EXT', '.html');
define ('INDEX', 'index');

/* DB connection settings */
define ('SCHEME', 'mysql');
define ('DBADDRESS', 'dbname=test;host=localhost');
define ('DBUSER', 'test');
define ('DBPASS', '123');

define ('_SAPE_USER', ''); // your SAPE id

if (SETTINGS_IS_DEBUG) {
    error_reporting(E_ALL ^ E_NOTICE);
    ini_set('display_errors', true);
}

ini_set('max_execution_time', 120);
setlocale(LC_ALL, 'ru_RU.' . INNERCODEPAGE);
mb_internal_encoding(INNERCODEPAGE);
date_default_timezone_set('Europe/Moscow');

require_once LIBS . DS . "Autoloader.php";
Autoloader::register();

Locator::add(CacheFile::getInstance()); // you can try CacheAPC if you have php APC extension
Locator::add(DB::getInstance());

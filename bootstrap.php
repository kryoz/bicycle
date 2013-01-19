<?php
define ('DEBUG', true); //dev mode on

define ('DS', DIRECTORY_SEPARATOR);
define ('DOCROOT', $_SERVER['DOCUMENT_ROOT'].DS); // root directory of the site
define ('ROOT', dirname(__FILE__).DS); // filesystem root of framework
define ('URLROOT', ''); // URL root of framework

define ('PROTOCOL', 'http'); // HTTP or HTTPS
define ('SEFENABLED', false); // SEF-url routes

define ('COMPONENTS', DOCROOT.'components'.DS);  // path to your applications
define ('LIBS', ROOT.'libs'.DS); // path to shared libraries
define ('LIBS2', DOCROOT.'libs'.DS); // site specific libs
define ('GLOBALVIEWS', DOCROOT.'tmpl'.DS); // path to global templates
define ('LOGFILE', DOCROOT.'debug.log'); // make sure that file has write-enable rights

define ('CACHEDIR', DS.'tmp'.DS); 
define ('CACHETTL', 3600); // cache time to live. '0' to disable 
define ('CP', md5(DOCROOT).'_');

define ('CODEPAGE', 'utf-8'); // HTML codepage
define ('INNERCODEPAGE', 'utf8'); // inner codepage

define ('VIRT_EXT', '.html');
define ('INDEX', 'index');

/* DB connection settings */
define ('SCHEME', 'mysql'); 
define ('DBADDRESS', 'dbname=test;host=localhost');
define ('DBUSER', 'admin');
define ('DBPASS', 'pass');

define ('_SAPE_USER', '2b6b81f35caca7b76766fa558d1eadd1'); // your SAPE id

if (DEBUG)
{
    error_reporting(E_ALL ^ E_NOTICE);
    ini_set('display_errors', true);
}

ini_set('max_execution_time', 120);
setlocale(LC_ALL, 'ru_RU.'.INNERCODEPAGE);
mb_internal_encoding( INNERCODEPAGE );
date_default_timezone_set( 'Europe/Moscow' );


require_once LIBS.'Cache'.DS.'Cache.php';
require_once LIBS."autoloader.php";
\Core\Autoloader::register();
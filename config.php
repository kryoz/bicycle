<?php
define ('DEBUG', true); //dev mode on

define ('DOCROOT', $_SERVER['DOCUMENT_ROOT']); // root directory of the site
define ('DS', DIRECTORY_SEPARATOR);
define ('ROOT', dirname(__FILE__).DS); // filesystem root of framework
define ('URLROOT', substr(ROOT, strlen(rtrim(DOCROOT, '/'))) ); // URL root of framework
define ('PROTOCOL', 'http'); // HTTP or HTTPS

define ('COMPONENTS', ROOT.'components'.DS);  // path to your applications
define ('LIBS', ROOT.'libs'.DS); // path to shared libraries
define ('GLOBALVIEWS', ROOT.'tmpl'.DS); // path to global templates

define ('CACHEDIR', DS.'tmp'.DS); 
define ('CACHETTL', 3600); // cache time to live. '0' to disable 
define ('CP', 'tst_');

define ('CODEPAGE', 'utf-8'); // HTML codepage
define ('INNERCODEPAGE', 'utf-8'); // inner codepage

define ('VIRT_EXT', '.html');
define ('INDEX', 'index');

/* DB connection settings */
define ('SCHEME', 'mysql'); 
define ('DBADDRESS', 'dbname=test;host=localhost');
define ('DBUSER', 'admin');
define ('DBPASS', 'pass');

if (DEBUG)
{
    error_reporting(E_ALL ^ E_NOTICE);
    ini_set('display_errors', true);
}

ini_set('max_execution_time', 120);
setlocale(LC_ALL, 'ru_RU.'.INNERCODEPAGE);
mb_internal_encoding( INNERCODEPAGE );
date_default_timezone_set( 'Europe/Moscow' );
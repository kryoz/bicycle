<?php
define ('DEBUG', false);

define ('DOCROOT', $_SERVER['DOCUMENT_ROOT']); // Корень сайта
define ('DS', DIRECTORY_SEPARATOR);
define ('ROOT', dirname(__FILE__).DS); // Корень приложения в файловой системе
define ('COMPONENTS', ROOT.'components'.DS); 
define ('LIBS', ROOT.'libs'.DS); // Путь для автозагрузки классов
define ('GLOBALVIEWS', ROOT.'tmpl'.DS); // Путь к глобальным шаблонам

define ('CACHEDIR', ROOT.'cache'.DS); // Путь для файлов кэша
define ('CACHETTL', 86400*7); // Время жизни кэша. 0 - чтобы отключить
define ('CODEPAGE', 'windows-1251'); // Кодировка HTML
define ('INNERCODEPAGE', 'cp1251'); // Кодировка БД и строковых преобразований
define ('URLROOT', '/appfolder/'); // Корень приложения (URL)

/* Данные для подключения БД */
define ('SCHEME', 'mysql'); 
define ('DBADRESS', 'dbname=testdb;host=localhost');
define ('DBUSER', 'mylogin');
define ('DBPASS', 'mypass');

if (DEBUG)
{
    error_reporting(E_ALL ^ E_NOTICE);
    ini_set('display_errors', true);
}

ini_set('max_execution_time', 120);
setlocale( LC_ALL, 'ru_RU.UTF-8');
mb_internal_encoding( 'UTF-8' );
date_default_timezone_set( 'Europe/Moscow' );
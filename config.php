<?php
define ('DEBUG', true);

define ('DOCROOT', $_SERVER['DOCUMENT_ROOT']); // Корень сайта
define ('DS', DIRECTORY_SEPARATOR);
define ('ROOT', dirname(__FILE__).DS); // Корень приложения в файловой системе
define ('COMPONENTS', ROOT.'components'.DS); 
define ('LIBS', ROOT.'libs'.DS); // Путь для автозагрузки классов
define ('GLOBALVIEWS', ROOT.'tmpl'.DS); // Путь к глобальным шаблонам

define ('CACHEDIR', ROOT.'cache'.DS); // Путь для файлов кэша
define ('CACHETTL', 86400*7); // Время жизни кэша. 0 - чтобы отключить
define ('CODEPAGE', 'utf-8'); // Кодировка HTML
define ('INNERCODEPAGE', 'utf-8'); // Кодировка БД и строковых преобразований
define ('URLROOT', '/bicycle/'); // Корень приложения (URL)

/* Данные для подключения БД */
define ('SCHEME', 'mysql'); 
define ('DBADRESS', 'dbname=test;host=localhost');
define ('DBUSER', 'admin');
define ('DBPASS', '');

if (DEBUG)
{
    error_reporting(E_ALL ^ E_NOTICE);
    ini_set('display_errors', true);
}

ini_set('max_execution_time', 120);
setlocale( LC_ALL, 'ru_RU.UTF-8');
mb_internal_encoding( 'UTF-8' );
date_default_timezone_set( 'Europe/Moscow' );
<?php
define ('DEBUG', false);

define ('DOCROOT', $_SERVER['DOCUMENT_ROOT']); // ������ �����
define ('DS', DIRECTORY_SEPARATOR);
define ('ROOT', dirname(__FILE__).DS); // ������ ���������� � �������� �������
define ('COMPONENTS', ROOT.'components'.DS); 
define ('LIBS', ROOT.'libs'.DS); // ���� ��� ������������ �������
define ('GLOBALVIEWS', ROOT.'tmpl'.DS); // ���� � ���������� ��������

define ('CACHEDIR', ROOT.'cache'.DS); // ���� ��� ������ ����
define ('CACHETTL', 86400*7); // ����� ����� ����. 0 - ����� ���������
define ('CODEPAGE', 'windows-1251'); // ��������� HTML
define ('INNERCODEPAGE', 'cp1251'); // ��������� �� � ��������� ��������������
define ('URLROOT', '/appfolder/'); // ������ ���������� (URL)

/* ������ ��� ����������� �� */
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
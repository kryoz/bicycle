<?php
class Model_test
{
	function anothertest()
	{
		// Подключение к базе. Только пример кода, т.к. базы тут нет
		$db = new dbPDO();

		// параметры для передачи в запрос. Вручную экранировать не нужно
		$params = array("param1"=>"asd", "param2" => "qwerty");

		// Есть еще 3-й параметр query для различных опций компоновки результата, см. http://php.net/fetchall
		$res = $db->query("SELECT * FROM table WHERE column1 = :param1 AND column2 = :param2", $params); 

		return $res;
	}
}
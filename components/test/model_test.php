<?php
class Model_test
{
	function anothertest()
	{
		$db = dbPDO::getInstance();

		$params = array("param1"=>"asd", "param2" => "qwerty");

		$res = $db->query("SELECT * FROM table WHERE column1 = :param1 AND column2 = :param2", $params); 

		return $res;
	}
}
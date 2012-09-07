<?php
class Model_test
{
	function anothertest()
	{
		// РџРѕРґРєР»СЋС‡РµРЅРёРµ Рє Р±Р°Р·Рµ. РўРѕР»СЊРєРѕ РїСЂРёРјРµСЂ РєРѕРґР°, С‚.Рє. Р±Р°Р·С‹ С‚СѓС‚ РЅРµС‚
		$db = new dbPDO();

		// РїР°СЂР°РјРµС‚СЂС‹ РґР»СЏ РїРµСЂРµРґР°С‡Рё РІ Р·Р°РїСЂРѕСЃ. Р’СЂСѓС‡РЅСѓСЋ СЌРєСЂР°РЅРёСЂРѕРІР°С‚СЊ РЅРµ РЅСѓР¶РЅРѕ
		$params = array("param1"=>"asd", "param2" => "qwerty");

		// Р•СЃС‚СЊ РµС‰Рµ 3-Р№ РїР°СЂР°РјРµС‚СЂ query РґР»СЏ СЂР°Р·Р»РёС‡РЅС‹С… РѕРїС†РёР№ РєРѕРјРїРѕРЅРѕРІРєРё СЂРµР·СѓР»СЊС‚Р°С‚Р°, СЃРј. http://php.net/fetchall
		$res = $db->query("SELECT * FROM table WHERE column1 = :param1 AND column2 = :param2", $params); 

		return $res;
	}
}
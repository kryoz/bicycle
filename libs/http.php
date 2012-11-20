<?php
class HTTP extends Model_Base {
	public static function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
    }

    public function sendJSON($json)
    {
        if ($json) {

            $json = json_encode($json);
            
            $response = '';

            // смотрим признак нашего jsonp-запроса по названию callback-функции
            if (isset($_GET['callback'])) 
            {
                // если таки оно, то оборачиваем данные в функцию
                header("content-type: text/javascript");
                $response = $_GET['callback'].'('.$json.')';
            }
            else
            {
                header("content-type: text/json");
                $response = $json;
            }
            // строка получена - отдадим ее
            echo $response;
        } 
    }
}
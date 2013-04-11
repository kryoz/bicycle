<?php
namespace Site;

class Http
{
	public static function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
    }

    public function sendJSON($json)
    {
        if ($json) {

            $json = json_encode($json);

            $response = '';

            if (isset($_GET['callback'])) {
                header("content-type: text/javascript");
                $response = $_GET['callback'].'('.$json.')';
            } else {
                header("content-type: text/json");
                $response = $json;
            }

            echo $response;
        }
    }

    public function getGet()
    {
        return $_GET;
    }

    public function getPost()
    {
        return $_POST;
    }

    public function getGetVar($key)
    {
        return $_GET[$key];
    }

    public function getPostVar($key)
    {
        return $_POST[$key];
    }
}
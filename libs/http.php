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

            // ������� ������� ������ jsonp-������� �� �������� callback-�������
            if (isset($_GET['callback'])) 
            {
                // ���� ���� ���, �� ����������� ������ � �������
                header("content-type: text/javascript");
                $response = $_GET['callback'].'('.$json.')';
            }
            else
            {
                header("content-type: text/json");
                $response = $json;
            }
            // ������ �������� - ������� ��
            echo $response;
        } 
    }
}
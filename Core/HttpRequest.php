<?php
/**
 * Created by PhpStorm.
 * User: kryoz
 * Date: 02.11.13
 * Time: 23:54
 */

namespace Core;


class HttpRequest
{
    public function getGet()
    {
        return $_GET;
    }

    public function getPost()
    {
        return $_POST;
    }

    public function getRequest()
    {
        return $_REQUEST;
    }

    public function getRequestUrl()
    {
        return $_SERVER['REQUEST_URI'];
    }
} 
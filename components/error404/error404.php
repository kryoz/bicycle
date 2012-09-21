<?php
/**
 * 404 error handler
 *
 * @author kubintsev
 */
class Controller_error404 extends Controller_Base {
    
    function index($args, $params) 
    {
        $model = $this->model;
        $view = $this->view;

        $view->loadTemplate("error404")->render();
    }
}

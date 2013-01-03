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

        $view->setGlobalTemplate('404.php')->loadTemplate("error404")->render();
    }
}

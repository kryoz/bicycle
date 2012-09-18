<?php
/**
 * Description of sqlite
 *
 * @author kubintsev
 */
class Controller_test extends Controller_Base
{
    function index($args)
    {
        $view = $this->view;
        $model = $this->model;
        
        $vars['data'] = $model->anothertest();
        
        $view->loadTemplate('test');
        $view->loadVars($vars)->render();
    }
}

?>

<?php
/**
 * Description of sqlite
 *
 * @author kubintsev
 */
class Controller_test extends Controller_Base
{
    function index($args, $params)
    {
        $model = $this->model;
        $view = $this->view;
        
        $vars['title'] = 'You called another component - TEST';
        $vars['args'] = print_r($args,1);

        $view->loadTemplate("test")->loadVars($vars)->render();
    }
}

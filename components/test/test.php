<?php
namespace Components;

class CTest extends \Core\BaseController
{
	public $complex = true;
	
    function index($args, $params)
    {
        $view = $this->view;
        
        $vars['title'] = 'You called another component - TEST';
        $vars['args'] = print_r($args,1);
        $vars['params'] = print_r($params,1);

        $view->loadTemplate("view_test")->loadVars($vars)->render();
    }
}

<?php
namespace Components\Test;

use Core\View;

class Test extends \Site\BaseController
{
    public $complex = true;

    public function index($args, $params)
    {
        $view = $this->view;

        $vars['title'] = 'You called another component - TEST';
        $vars['args'] = print_r($args, 1);
        $vars['params'] = print_r($params, 1);

        $view->loadTemplate("view_test")->loadVars($vars)->render();
    }
}

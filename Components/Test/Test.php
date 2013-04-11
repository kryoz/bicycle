<?php
namespace Components\Test;

use Core\View;

class Test extends \Site\BaseController
{
    public $complex = true;
    private $view;

    public function __construct()
    {
        $this->view = new View();
        $this->view->setPath(__DIR__ . DS);
    }

    function index($args, $params)
    {
        $view = $this->view;

        $vars['title'] = 'You called another component - TEST';
        $vars['args'] = print_r($args, 1);
        $vars['params'] = print_r($params, 1);

        $view->loadTemplate("view_test")->loadVars($vars)->render();
    }
}

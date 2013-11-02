<?php
namespace Components\Test;

use Core\HttpRequest;
use Core\View;
use Site\BaseController;

class Index extends BaseController
{
    public function defaultAction(HttpRequest $request)
    {
        $view = $this->defaultView;

        $vars['title'] = 'You called another component - TEST';
        $vars['args'] = print_r($request->getGet(), 1);

        $view->loadTemplate("view_test")->loadVars($vars)->render();
    }
}

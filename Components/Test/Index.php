<?php
namespace Components\Test;

use Core\HttpRequest;
use Core\ServiceLocator\Locator;
use Site\BaseController;
use Site\Router\Router;

class Index extends BaseController
{
    public function defaultAction(HttpRequest $request)
    {
        if (!Locator::get('sessionManager')->getUser()) {
            Router::NoAccess();
            return;
        }

        $vars['title'] = 'You called another component - TEST';
        $vars['args'] = print_r($request->getGet(), 1);

        $this->defaultView
            ->loadTemplate("view_test")
            ->loadVars($vars)
            ->render();
    }
}

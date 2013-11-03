<?php
namespace Components\Index;

use Core\HttpRequest;
use Core\ServiceLocator\Locator;
use Site\BaseController;
use Site\FormToken;

class Index extends BaseController
{
    protected $map = [
        'second' => 'secondAction',
        'tokencheck' => 'verifyAction',
        'logout' => 'logoutAction'
    ];

    public function defaultAction(HttpRequest $request)
    {
        $this->fillIndexPage();
    }

    public function secondAction(HttpRequest $request)
    {
        $vars['title'] = 'You called parameterized action';
        $vars['args'] = print_r($request->getGet(), 1);

        $this->defaultView
            ->loadTemplate("view_second")
            ->loadVars($vars)
            ->render();
    }

    public function verifyAction(HttpRequest $request)
    {
        $user = Locator::get('sessionManager')->getUser();

        $vars['loginResult'] = $user ? 'Success!' : 'Failed to login!';
        $this->defaultView
            ->loadTemplate("view_validation")
            ->loadVars($vars)
            ->render();
    }

    public function logoutAction(HttpRequest $request)
    {
        Locator::get('sessionManager')->logout();
        $this->fillIndexPage();
    }

    private function fillIndexPage()
    {
        $model = new CacheTest();

        $vars['prev'] = $model->getCache() ? : [];
        $vars['data'] = $model->generate();
        $vars['token'] = FormToken::create()->getToken();

        $this->defaultView
            ->loadTemplate("view_index")
            ->loadVars($vars)
            ->render();
    }
}
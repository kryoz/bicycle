<?php
namespace Components\Index;

use Core\HttpRequest;
use Site\BaseController;
use Site\FormToken;

class Index extends BaseController
{
    protected $map = [
        'second' => 'second',
        'tokencheck' => 'verifyToken'
    ];

    protected function defaultAction(HttpRequest $request)
    {
        session_set_cookie_params(3600);
        session_start();

        $model = new CacheTest();

        $vars['title'] = 'Example to show work of caching';
        $vars['prev'] = $model->getCache() ? : [];
        $vars['data'] = $model->generate();
        $vars['token'] = FormToken::create()->getToken();

        $this->defaultView
            ->loadTemplate("view_index")
            ->loadVars($vars)
            ->render();
    }

    protected function second(HttpRequest $request)
    {
        $vars['title'] = 'You called parameterized action';
        $vars['args'] = print_r($request->getGet(), 1);

        $this->defaultView
            ->loadTemplate("view_second")
            ->loadVars($vars)
            ->render();
    }

    protected function verifyToken(HttpRequest $request)
    {
        session_set_cookie_params(3600);
        session_start();

        $vars['input'] = $request->getPost()['sometext'];
        $vars['valid'] = FormToken::create()->validateToken(true);

        $this->defaultView
            ->loadTemplate("view_validation")
            ->loadVars($vars)
            ->render();
    }

}
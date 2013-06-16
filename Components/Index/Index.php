<?php
namespace Components\Index;

use Site\BaseController;
use Site\FormToken;

class Index extends BaseController
{
    public function index($args, $params)
    {
        session_set_cookie_params(3600);
        session_start();

        $model = new CacheTest();

        $vars['title'] = 'Example to show work of caching';
        $vars['prev'] = $model->getCache();
        $vars['data'] = $model->generate();
        $vars['token'] = FormToken::create()->getToken();

        $this->view
            ->loadTemplate("view_index")
            ->loadVars($vars)
            ->render();
    }

    public function second($args, $params)
    {
        $vars['title'] = 'You called parameterized action';
        $vars['args'] = print_r($args, 1);

        $this->view
            ->loadTemplate("view_second")
            ->loadVars($vars)
            ->render();
    }

    public function tokencheck($args, $params)
    {
        session_set_cookie_params(3600);
        session_start();

        $vars['input'] = $_POST['sometext'];
        $vars['valid'] = FormToken::create()->validateToken(true);

        $this->view
            ->loadTemplate("view_validation")
            ->loadVars($vars)
            ->render();
    }

}
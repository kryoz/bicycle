<?php
namespace Components\Index;

use Core\View;

class Index extends \Site\BaseController
{
    /**
     * @var \Core\View
     */
    private $view;

    public function __construct()
    {
        $this->view = new View();
        $this->view->setPath(__DIR__ . DS);
    }

    public function index($args, $params)
    {
        $model = new CacheTest();

        $vars['title'] = 'Example to show work of caching';
        $vars['prev'] = $model->getCache();
        $vars['data'] = $model->generate();

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

}
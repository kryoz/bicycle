<?php
namespace Components;

class CIndex extends \Core\BaseController
{
    public function index($args, $params) 
    {
        $model = $this->loadModel('Components\index\cachetest');
        $view = $this->view;
        
        $vars['title'] = 'Example to show work of caching';
        $vars['prev'] = $model->getCache();
        $vars['data'] = $model->generate();

        $view->loadTemplate("view_index")->loadVars($vars)->render();
    }
    
    public function second($args, $params)
    {
        $view = $this->view;
        
        $vars['title'] = 'You called parameterized action';
        $vars['args'] = print_r($args,1);

        $view->loadTemplate("view_second")->loadVars($vars)->render();
    }

}
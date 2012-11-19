<?php

class Controller_index extends Controller_Base 
{
    public function index($args, $params) 
    {
        $model = $this->model;
        $view = $this->view;
        
        $vars['title'] = 'Example to show work of caching';
        $vars['prev'] = $model->getCache();
        $vars['data'] = $model->generate();

        $view->loadTemplate("index")->loadVars($vars)->render();
    }
    
    public function second($args, $params)
    {
        $model = $this->model;
        $view = $this->view;
        
        $vars['title'] = 'You called parameterized action';
        $vars['args'] = print_r($args,1);

        $view->loadTemplate("second")->loadVars($vars)->render();
    }

}
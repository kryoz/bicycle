<?php

class Controller_index extends Controller_Base 
{
            
    function Run($args) 
    {
        $model = $this->model;
        $view = $this->view;
        
        $vars['title'] = 'Example to show work of caching';
        $vars['args'] = $args;
        $vars['prev'] = $model->getCache();
        $vars['data'] = $model->generate();

        $view->loadTemplate("cache")->loadVars($vars)->render();

    }

}
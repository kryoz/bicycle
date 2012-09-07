<?php

class Controller_index extends Controller_Base 
{
            
    function Run($args) 
    {
        $model = $this->model;
        $view = $this->view;
        
        $vars['title'] = 'Ѕронирование авиабилетов на все направлени€';
        $vars['args'] = $args;
        $vars['data'] = $model->test();

        $view->loadTemplate("countrylist"); 

        $view->loadVars($vars)->render();

    }

}
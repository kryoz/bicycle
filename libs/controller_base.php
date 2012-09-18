<?php
/**
 * Базовый класс контроллера
 *
 * @author kubintsev
 */
abstract class Controller_Base 
{
    protected $controller_path;
    protected $model;
    protected $view;
    protected $name;
    
    /**
     * Tries to load default model class and view
     * @param string $name 
     */
    final function __construct($name) 
    {
        $this->name = $name;
        $this->controller_path = COMPONENTS.$name.DS;
        
        $model_name = strtolower("model_$name");
        
        $this->model = $this->loadModel($name, $name);
        
        $this->view = new View();
        $this->view->setPath($this->controller_path);
    }
    
    /**
     * Loads model from any component
     * @param string $component
     * @param string $name
     * @return object|boolean
     */
    final function loadModel($component, $name)
    {
        $component = COMPONENTS.strtolower($component).DS;
        $model_name = strtolower("model_$name");
        $model_fn = $component.$model_name.'.php';
        if (file_exists($model_fn))
        {
            require_once $model_fn;
            return new $model_name();
        }
        else
            return false;
    }

    /**
     * Entry point to controller
     * @param mixed $args string or array from 
     */
    abstract function index($args); 
}
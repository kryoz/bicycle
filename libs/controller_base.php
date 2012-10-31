<?php
/**
 * Controller base class
 *
 * @author kubintsev
 */
abstract class Controller_Base 
{
    protected $controller_path;
    protected $model;
    protected $view;
    protected $name; //controller name
    
    protected $vars;
    protected static $args;
    
    /**
     * Tries to load default model class and view
     * @param string $name 
     */
    function __construct($name) 
    {
        $this->name = $name;
        $this->controller_path = COMPONENTS.$name.DS;
        
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
            $model = new $model_name();
            
            if (is_callable($model_name, 'setComponentName'))
                $model->setComponentName($this->name);
            
            return $model;
        }
        else
            return false;
    }

    /**
     * Entry point to controller
     * @param mixed $args string or array from 
     */
    abstract function index($args, $params); 
    
    /**
     * Генератор навигационной строки
     * @param type $level
     * @return type
     */
    protected function buildNav($level)
    {
        $vars = $this->vars;
        $titles = array();
        
        for ($i = 0; $i < $level; $i++)
            $titles[] = '<a href="'.Catalogue::makeURL(self::$args, $i).'">'.$vars[$i]['navtitle'].'</a>';
        
        $titles[] = $vars[$i]['navtitle'];
        
        return implode(' - ', $titles);
    }
}
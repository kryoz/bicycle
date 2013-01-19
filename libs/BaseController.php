<?php
/**
 * Controller base class
 *
 * @author kryoz
 */
namespace Core;

abstract class BaseController
{
    protected $view;
    protected $vars;
    protected static $args;
    
    /**
     * Tries to load default model class and view
     */
    public function __construct($path) 
    {
        $this->view = (new View)->setPath($path);
    }
    
    /**
     * Loads model
     * @param string $component
     * @param string $name
     * @return object|boolean
     */
    final function loadModel($name)
    {
		$model = new $name();
		return $model;
    }

    /**
     * Entry point to controller
     * @param mixed $args string or array from 
     * @param array $params array of key=value pairs from url
     */
    abstract function index($args, $params); 
}
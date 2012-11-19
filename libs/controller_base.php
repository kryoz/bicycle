<?php
/**
 * Controller base class
 *
 * @author kubintsev
 */
abstract class Controller_Base extends Model_Base
{
    protected $controller_path;
    protected $model;
    protected $view;
    protected $vars;
    protected static $args;
    public $name;
    
    /**
     * Tries to load default model class and view
     * @param string $name 
     */
    public function __construct() 
    {
        parent::__construct();
        $this->controller_path = COMPONENTS.$this->name.DS;
        
        $this->model = $this->loadModel($this->name, $this->name);
        
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
        
        try {
            if (file_exists($model_fn))
            {
                require_once $model_fn;
                $model = new $model_name();

                return $model;
            }
            else
                throw new Exception("<b>$model_fn</b> was not found");
        } catch (Exception $e) {
            Debug::log(__CLASS__.'::'.__FUNCTION__.': '.$e->getMessage());
        }
    }

    /**
     * Entry point to controller
     * @param mixed $args string or array from 
     * @param array $params array of key=value pairs from url
     */
    abstract function index($args, $params); 
    
    /**
     * If params got unhandled then it means URL is wrong
     * @param mixed $args string or array from 
     */
    protected function checkArgs($args) {
        if (!empty($args))
            Router::NoPage();
    }
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
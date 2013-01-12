<?php
/**
 * Controller base class
 *
 * @author kryoz
 */
abstract class BaseController extends Component
{
    protected $controller_path;
    protected $view;
    protected $vars;
    protected static $args;
    public $name;
    
    /**
     * Tries to load default model class and view
     */
    public function __construct() 
    {
        parent::__construct();
        $this->controller_path = COMPONENTS.strtolower(substr($this->name, 1, strlen($this->name))).DS;
        $this->view = new View();
        $this->view->setPath($this->controller_path);
    }
    
    /**
     * Loads model from any component
     * @param string $component
     * @param string $name
     * @return object|boolean
     */
    final function loadModel($name, $component = null)
    {
        $component = $component ? $component : $this->controller_path;
        $model_name = strtolower($name);
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
}
<?php
/**
 * ������� ����� �����������
 *
 * @author kubintsev
 */
abstract class Controller_Base 
{
    protected $controller_path;
    protected $model;
    protected $view;
    
    final function __construct($name) {
        $this->controller_path = COMPONENTS.$name.DS;
        
        $model_name = "Model_$name";
        $model_fn = $this->controller_path.strtolower($model_name).'.php';
        if (file_exists($model_fn))
        {
            require_once $model_fn;
            $this->model = new $model_name();
        }
        
        $this->view = new View();
        $this->view->setPath($this->controller_path);
    }
    /**
     * ������� ����� �����������
     * @param mixed $args ������� ��� ������ ���������� �� ������� URL, ������������ Router
     */
    abstract function Run($args); 
}

?>

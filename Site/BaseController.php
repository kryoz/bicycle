<?php
/**
 * Controller base class
 *
 * @author kryoz
 */
namespace Site;

use Core\View;

abstract class BaseController
{
    protected $vars;
    protected static $args;

    protected $view;

    public function __construct()
    {
        $this->view = new View();
        $classInfo = new \ReflectionClass($this);
        $this->view->setPath(dirname($classInfo->getFileName()) . DS);
    }

    /**
     * Entry point to controller
     * @param mixed $args string or array from
     * @param array $params array of key=value pairs from url
     */
    abstract function index($args, $params);
}
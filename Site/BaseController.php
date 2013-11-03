<?php
namespace Site;

use Core\HttpRequest;

abstract class BaseController
{
    protected $defaultView;
    protected $map = [];

    public function __construct()
    {
        $this->defaultView = new View();
        $classInfo = new \ReflectionClass($this);
        $this->defaultView->setPath(dirname($classInfo->getFileName()) . DS);
        $this->map += ['defaultAction' => 'defaultAction'];
    }

    public function getMap()
    {
        return $this->map;
    }

    abstract protected function defaultAction(HttpRequest $request);
}
<?php
namespace Site;

use Core\HttpRequest;
use Site\Router\RouterStrategy;

abstract class BaseController
{
    protected $defaultView;
    protected $map = null;

    public function __construct()
    {
        $this->defaultView = new View();
        $classInfo = new \ReflectionClass($this);
        $this->defaultView->setPath(dirname($classInfo->getFileName()) . DS);
    }

    public function handleRequest(HttpRequest $request)
    {
        if (!isset($this->map[RouterStrategy::getPage()])) {
            $this->defaultAction($request);
            return;
        }

        $this->{$this->map[RouterStrategy::getPage()]}($request);
    }

    abstract protected function defaultAction(HttpRequest $request);
}
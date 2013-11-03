<?php
/**
 * Created by PhpStorm.
 * User: kryoz
 * Date: 03.11.13
 * Time: 12:30
 */

namespace Core\Chain;

use Core\HttpRequest;

class ChainContainer
{
    /**
     * @var ChainInterface[]
     */
    protected $handlers = [];
    protected $request;

    /**
     * @param HttpRequest $request
     * @return $this
     */
    public function setRequest(HttpRequest $request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * @return HttpRequest
     */
    public function getRequest()
    {
        return $this->request;
    }

    public function run()
    {
        foreach ($this->handlers as $handler) {
            if ($handler->handleRequest($this->getRequest()) === false) {
                break;
            }
        }
    }
    public function addHandler(ChainInterface $handler)
    {
        $this->handlers[] = $handler;
        return $this;
    }
} 
<?php
/**
 * Created by PhpStorm.
 * User: kryoz
 * Date: 03.11.13
 * Time: 15:36
 */

namespace Site\Filters;


use Core\Chain\ChainInterface;
use Core\ServiceLocator\Locator;
use Site\SessionManager;

class SessionFilter implements ChainInterface
{
    public function handleRequest($request)
    {
        $sessionManager = new SessionManager();
        $sessionManager->login($request);
        Locator::add('sessionManager', $sessionManager);
    }
}
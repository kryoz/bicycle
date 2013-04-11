<?php
/**
 * Controller base class
 *
 * @author kryoz
 */
namespace Site;

abstract class BaseController
{
    protected $vars;
    protected static $args;

    /**
     * Entry point to controller
     * @param mixed $args string or array from
     * @param array $params array of key=value pairs from url
     */
    abstract function index($args, $params);
}
<?php
/**
 * Created by PhpStorm.
 * User: kryoz
 * Date: 02.11.13
 * Time: 14:36
 */

namespace Core;


trait TSingleton
{
    protected static $instance;

    final static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new static();
        }

        return static::$instance;
    }
} 
<?php
namespace Core\Form;

class Rules
{
    public static function notNull() 
    { 
        return function ($val) {
            return (bool) $val;
        };
    }
    
    public static function email() 
    { 
        return function ($val) {
            return preg_match("/^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,4}$/i", trim($val));
        };
    }
}


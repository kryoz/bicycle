<?php
/**
 * Description of Debug
 *
 * @author kubintsev
 */

class Debug 
{
    private static $mem = array();
    private static $log;
    
    public static function mem($step)
    {
        self::$mem[$step] = memory_get_usage();
    }
    
    public static function log($str)
    {
        self::$log[] = $str;
    }
    
    public static function dprint($var, $echo = 0)
    {
        $dump = "<pre style=\"border: 1px solid #000; padding:1em; margin: 0.5em;\">";
        $dump .= print_r($var, true);
        $dump .= "</pre>\n";

        if ($echo)
            echo $dump;
        else
            self::$log[] = $dump;
    }
    
    public static function getlog()
    {
        $str = '';
        if ( !empty(self::$log) )
            $str = implode('<br />', self::$log);
        return $str;
    }
    
    public static function getmem()
    {
        if (!empty(self::$mem))
        {
            $str = 'Memory usage:<br>';
        }
        foreach (self::$mem as $step => $mem)
        {
            $str .= "&nbsp;&nbsp;&nbsp;&nbsp;[$step] => ".round($mem/1048576, 2)."Mb<br />";
        }
        return $str;
    }
}

<?php
/**
 * Description of Debug
 *
 * @author kubintsev
 */

class Debug 
{
    private static $mem = array();
    private static $memcount = 0;
    private static $log;
    private static $timer = array();
    
    public static function mem($step = false)
    {
        if ( $step === false )
            $step = self::$memcount;
        
        self::$mem[$step] = memory_get_usage();
        self::$memcount++;
    }
    
    public static function log($str)
    {
        self::$log[] = $str;
    }
    
    public static function pstart($title = 'your code')
    {
        self::$timer['stamp'] = microtime(true);
        self::$timer['title'] = $title;
    }
    
    public static function pmeasure()
    {
        if ( !isset(self::$timer['stamp']))
            self::$timer['stamp'] = microtime(true);
            
        self::$timer['stamp'] = microtime(true) - self::$timer['stamp'];
        
        Debug::log('PROFILER measured '.sprintf('%s sec', round(self::$timer['stamp'], 3)).' on '.self::$timer['title']);
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

<?php
/**
 * Description of Debug
 *
 * @author kubintsev
 */

class Debug 
{
    public $timestamp;
    public $mem = array();
    public $memcount = 0;
    public $log;
    public $timer = array();
    private static $instance;

    private function __construct()
    {
        $this->timestamp = date('Y-m-d H:i:s');
    }

    public function __destruct()
    {
        if (defined('LOGFILE')) {
                if ($f = @fopen(LOGFILE, 'a+')) {
                fputs($f, "\n*****************************[ {$this->timestamp} ]*******************************\n");
                foreach ($this->log as $line) {
                    fputs($f, $line);
                }
                fclose($f);
            }
        }

        foreach ($this as $key => $value) { 
            unset($this->$key); 
        } 
    }

    public static function getInstance()
    {
        if ( empty( self::$instance))
        {
            self::$instance = new Debug();
        } 
        
        return self::$instance;
        
    }
    
    public static function mem($step = false)
    {
        $debug = self::getInstance();
        if ( $step === false )
            $step = $debug->memcount;
        
        $debug->mem[$step] = memory_get_usage();
        $debug->memcount++;
    }
    
    public static function log($obj)
    {
        $debug = self::getInstance();

        if ($obj instanceof Exception) {
            $debug->log[] = $obj->getMessage()."\n".$obj->getTraceAsString();
        }
        else {
            $debug->log[] = $obj;
        }
    }
    
    public static function pstart($title = 'your code')
    {
        $debug = self::getInstance();
        $debug->timer['stamp'] = microtime(true);
        $debug->timer['title'] = $title;
    }
    
    public static function pmeasure()
    {
        $debug = self::getInstance();
        if ( !isset($debug->timer['stamp']))
            $debug->timer['stamp'] = microtime(true);
            
        $debug->timer['stamp'] = microtime(true) - $debug->timer['stamp'];
        
        Debug::log('PROFILER measured '.sprintf('%s sec', 
            round($debug->timer['stamp'], 3)).' on '.$debug->timer['title']);
    }
    
    public static function dprint($var, $echo = 0)
    {
        $dump = print_r($var, true);

        if ($echo)
            echo $dump;
        else {
            $debug = self::getInstance();
            $debug->log[] = $dump;
        }
    }
    
    public static function getlog()
    {
        $str = '';
        $debug = self::getInstance();
        if (!empty($debug->log) )
            $str = implode('<br />', $debug->log);
        $debug::dprint($str);
        return end($debug->log);
    }
    
    public static function getmem()
    {
        $str = '';
        $debug = self::getInstance();
        if (!empty($debug->mem))
        {
            $str = 'Memory usage:<br>';
        }
        foreach ($debug->mem as $step => $mem)
        {
            $str .= "&nbsp;&nbsp;&nbsp;&nbsp;[$step] => ".round($mem/1048576, 2)."Mb<br />";
        }
        return $str;
    }
}

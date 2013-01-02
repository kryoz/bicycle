<?php
/**
 * Base cache handler
 *
 * @author kubintsev
 */

class Cache implements ICache
{
    private $cache;
    protected static $instance;
        
    function __construct( $cachetype ) 
    {
        if ( !$cachetype )
        {
            if ( function_exists('apc_cache_info'))
                $cachetype = 'apc';
            else
                $cachetype = 'file';
        }

        $Cache_class = 'Cache_'.$cachetype;
        $class_file = LIBS.'cache'.DS.strtolower($Cache_class).'.php';
        
        if (file_exists($class_file)) {
            require_once $class_file;
        }
        else
            throw new Exception(__CLASS__.'::'.__FUNCTION__.": Cache type '$cachetype' not defined! Haven't found $class_file");
        
        $this->cache = new $Cache_class();
    }
    
    final static function getInstance($cachetype = null)
    {
        if ( empty( self::$instance) )
        {        
            self::$instance = new Cache($cachetype);
        }
        
        return self::$instance;
    }
    
    /**
     * Get data from cache
     * @param string Cell name
     * @return mixed Data from cache cell
     */
    function get($scope)
    {
        return $this->cache->get($scope);
    }
   
    
    /**
     * Saves to cache cell data
     * @param string $scope Cell name
     * @param mixed $data Data to save
     * @return boolean Success?
     */
    function set($scope, $data = null, $ttl = null)
    {
        $ttl = $ttl ? $ttl : CACHETTL;
        $this->cache->set($scope, $data, $ttl);
    }
    
    
    /**
     * Erases cache cell
     * @param string $scope cache cell name
     */
    function flush($scope, $regular = false)
    {
        $this->cache->flush($scope, $regular);
    }
    
}
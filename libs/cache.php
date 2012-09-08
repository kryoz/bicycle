<?php
/**
 * Base cache handler
 *
 * @author kubintsev
 */

class Cache implements ICache{
    protected static $instance;
        
    final static function getInstance($cachetype = false)
    {
        if ( !$cachetype )
        {
            if ( !function_exists('apc_cache_info'))
                $cachetype = 'file';
            else
                $cachetype = 'apc';
        }
        
        $Cache_class = 'Cache_'.$cachetype;
        
        if ( empty( self::$instance) )
        {
            require_once 'cache'.DS.strtolower($Cache_class).'.php';
        }
            self::$instance = new $Cache_class;
        
        return self::$instance;
    }
    
    /**
     * Get data from cache
     * @param string Cell name
     * @return mixed Data from cache cell
     */
    function get($scope)
    {
        return self::$instance->get($scope);
    }
   
    
    /**
     * Saves to cache cell data
     * @param string $scope Cell name
     * @param mixed $data Data to save
     * @return boolean Success?
     */
    function set($scope, $data = null)
    {
        self::$instance->set($scope, $data);
    }
    
    
    /**
     * Erases cache cell
     * @param string $scope cache cell name
     */
    function flush($scope)
    {
        self::$instance->flush($scope);
    }
    
}
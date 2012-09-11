<?php
/**
 * APC cache handler
 *
 * @author kryoz
 */
class cache_apc implements ICache
{
    function __construct() 
    {
        if ( !function_exists('apc_cache_info'))
            throw new Exception('CACHE_APC class error: APC module does not exist');
    }
        
    /**
     * 
     * @param string cell name corresponds to filename.txt
     * @return mixed 
     */
    function get($scope)
    {
        return apc_fetch($scope);
    }
    
    /**
     * 
     * @param string $scope 
     * @param mixed $data 
     * @return boolean
     */
    function set($scope, $data = null )
    {
        return apc_store($scope, $data, CACHETTL);
    }
    
    /**
     * 
     * @param string $scope
     */
    function flush($scope)
    {
        return apc_delete($scope);
    }
}

?>

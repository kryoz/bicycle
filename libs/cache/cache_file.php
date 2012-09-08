<?php
/**
 * File cache handler
 *
 * @author kubintsev
 */

class Cache_file implements ICache{
    
    function __construct() 
    {
        if ( !is_dir( CACHEDIR ) )
            throw new Exception('CACHE_FILE class error: cache directory "'.CACHEDIR.'" does not exist!');
    }
        
    /**
     * 
     * @param string cell name corresponds to filename.txt
     * @return mixed 
     */
    function get($scope)
    {
        $filename = CACHEDIR.$scope.'.txt';
            
        if ( CACHETTL == 0 || (file_exists($filename) && (time() - @filemtime($filename)) >= CACHETTL))
        {
            $this->flush($scope); // для удобства очистки кэша
            return false;
        }

        
        if ( file_exists($filename) )
        {
            return unserialize(file_get_contents($filename));
        }
    }
    
    /**
     * 
     * @param string $scope 
     * @param mixed $data 
     * @return boolean
     */
    function set($scope, $data = null )
    {
        $filename = CACHEDIR.$scope.'.txt';
        
        if ( $data !== null )
        {
            $fh = fopen($filename, 'w');
            fwrite($fh, serialize($data));
            fclose($fh);
        }
    }
    
    /**
     * 
     * @param string $scope
     */
    function flush($scope)
    {
        if (file_exists(CACHEDIR.$scope.'.txt'))
        {
            unlink(CACHEDIR.$scope.'.txt');
        }
    }
}

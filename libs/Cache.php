<?php
/**
 * Кэш
 *
 * @author kubintsev
 */

class Cache {
    private static $instance;
    private $init = false;
    
    function __construct() 
    {
        if ( !is_dir( CACHEDIR ) )
            throw new Exception('CACHE CLASS error: cache directory "'.CACHEDIR.'" does not exist!');
    }
    
    public static function getInstance()
    {
        if ( empty( self::$instance) )
            self::$instance = new Cache();
        
        return self::$instance;
    }
    
    /**
     * Метод для получения данных, который и реализует логику кэша
     * @param string имя области данных, ячейки, соответствует имени файла
     * @return mixed данные из кэша
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
     * Сохранение в кэше данных
     * @param string $scope Наименование ячейки кэша
     * @param mixed $data Сохраняемые данные
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
     * Удаляет ячейку кэша
     * @param string $scope Имя ячейки
     */
    function flush($scope)
    {
        if (file_exists(CACHEDIR.$scope.'.txt'))
        {
            unlink(CACHEDIR.$scope.'.txt');
        }
    }
}
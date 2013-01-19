<?php
namespace Components\index;

use Core\Cache\Cache;

class cachetest
{
    function generate()
    {
        $cache = Cache::getInstance();
        for ($i=0; $i < 20; $i++)
            $data[] = rand(1, 1000);
        
        $cache->set('index2', $data);
        
        return $data;
    }
    
    function getCache()
    {
        $cache = Cache::getInstance();
        return $cache->get('index2');
    }
}

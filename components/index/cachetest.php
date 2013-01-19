<?php
namespace Components\index;

use Core\ServiceLocator\Locator;

class cachetest
{
    function generate()
    {
        $cache = Locator::getInstance()->get('CACHE');
        for ($i=0; $i < 20; $i++)
            $data[] = rand(1, 1000);
        
        $cache->set('index2', $data);
        
        return $data;
    }
    
    function getCache()
    {
        $cache = Locator::getInstance()->get('CACHE');
        return $cache->get('index2');
    }
}

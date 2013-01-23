<?php
namespace Components\index;

use Core\ServiceLocator\Locator;

class cachetest
{
    public function generate()
    {
        $cache = Locator::get('CACHE');
        for ($i=0; $i < 20; $i++)
            $data[] = rand(1, 1000);
        
        $cache->set('index2', $data);
        
        return $data;
    }
    
    public function getCache()
    {
        $cache = Locator::get('CACHE');
        return $cache->get('index2');
    }
}

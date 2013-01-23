<?php
namespace Tests;
/**
 * Description of CacheTest
 *
 * @author kryoz
 */
class CacheTest extends \PHPUnit_Framework_TestCase
{
	
	public function testApcCacheHasSuccess() 
	{
		if (!extension_loaded('apc')) { 
			$this->markTestSkipped('Required APC not available');
		}
		//setup
		$cache = new \Core\Cache\Cache('Apc');
		//run
		$data=[];
        for ($i=0; $i < 20; $i++)
            $data[] = rand(1, 1000);
        
        $result = $cache->set('test123', $data);
		//verify
		$this->assertTrue($result, 'try to put in you php.ini apc.enable_cli=1');
		$this->assertTrue($cache->has('test123'));
		$this->assertEquals($cache->get('test123'), $data);
	}
	
	public function testFileCacheHasSuccess() 
	{
		//setup
		$cache = new \Core\Cache\Cache('File');
		//run
		$data=[];
        for ($i=0; $i < 20; $i++)
            $data[] = rand(1, 1000);
        
        $result = $cache->set('test123', $data);
		//verify
		$this->assertTrue($result);
		$this->assertTrue($cache->has('test123'));
		$this->assertEquals($cache->get('test123'), $data);
	}
}

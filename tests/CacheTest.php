<?php
namespace Tests;
use Core\Cache\CacheApc;
use Core\Cache\CacheFile;

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
		$cache = new CacheApc;
        $data = $this->makeTestArray();

		//run
        $result = $cache->set('test123', $data);

		//verify
		$this->assertTrue($result, 'try to put in you php.ini apc.enable_cli=1');
		$this->assertTrue($cache->has('test123'));
		$this->assertEquals($cache->get('test123'), $data);
	}
	
	public function testFileCacheHasSuccess() 
	{
		//setup
		$cache = new CacheFile;
        $data = $this->makeTestArray();

		//run
        $result = $cache->set('test123', $data);

		//verify
		$this->assertTrue($result);
		$this->assertTrue($cache->has('test123'));
		$this->assertEquals($cache->get('test123'), $data);
	}

    /**
     * @return array
     */
    private function makeTestArray()
    {
        $data = [];
        for ($i = 0; $i < 20; $i++) {
            $data[] = rand(1, 1000);
        }
        return $data;
    }
}

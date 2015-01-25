<?php
namespace Core\Utils;

class UUID
{
	public static function getPattern()
	{
		$block = '[a-f0-9]';
		return "~^$block{8}-$block{4}-$block{4}-$block{4}-$block{12}$~iu";
	}

	public static function check($value)
	{
		return preg_match(static::getPattern(), $value);
	}

	public static function get()
	{
		$uuid = array(
			'time_low' => 0,
			'time_mid' => 0,
			'time_hi' => 0,
			'clock_seq_hi' => 0,
			'clock_seq_low' => 0,
			'node' => array()
		);

		$uuid['time_low'] = microtime(1);
		$uuid['time_mid'] = mt_rand(0, 0xffff);
		$uuid['time_hi'] = (4 << 12) | (mt_rand(0, 0x1000));
		$uuid['clock_seq_hi'] = (1 << 7) | (mt_rand(0, 128));
		$uuid['clock_seq_low'] = mt_rand(0, 255);

		for ($i = 0; $i < 6; $i++) {
			$uuid['node'][$i] = mt_rand(0, 255);
		}

		return sprintf(
			'%08x-%04x-%04x-%02x%02x-%02x%02x%02x%02x%02x%02x',
			$uuid['time_low'],
			$uuid['time_mid'],
			$uuid['time_hi'],
			$uuid['clock_seq_hi'],
			$uuid['clock_seq_low'],
			$uuid['node'][0],
			$uuid['node'][1],
			$uuid['node'][2],
			$uuid['node'][3],
			$uuid['node'][4],
			$uuid['node'][5]
		);
	}

	public static function getShortUUID($value)
	{
		$block = '[a-f0-9]';
		$pattern = "~^($block{8})-$block{4}-$block{4}-($block{4})-$block{12}$~iu";
		return preg_replace($pattern, '$1-$2', $value);
	}
}

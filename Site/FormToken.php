<?php
namespace Site;

/**
 * This class helps to protect forms from CSRF vulnarability
 *
 * @author kryoz
 */
class FormToken extends Session
{

	const FIELDNAME = 'formtoken';
	const TTL = '7200';

	private function getSessionToken($prop)
	{
		return $this->get($prop, self::FIELDNAME);
	}

	private function setSessionToken($prop, $val)
	{
		$this->set($prop, $val, self::FIELDNAME);
	}

	public static function getToken()
	{
		$time = time();
		$token = sha1(mt_rand(0, 1000000));

		self::setSessionToken($token, $time);

		return "<input name='" . self::FIELDNAME . "' value='{$token}' type='hidden' />";
	}

	public static function validateToken($clear = true)
	{
		$valid = false;
		$posted = isset($_REQUEST[self::FIELDNAME]) ? $_REQUEST[self::FIELDNAME] : '';

		if (!empty($posted)) {
			if (self::getSessionToken($posted)) {
				if (self::getSessionToken($posted) >= time() - self::TTL) {
					$valid = true;
				}
				if ($clear)
					$this->remove($posted, self::FIELDNAME);
			}
		}

		return $valid;
	}

}

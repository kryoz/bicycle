<?php
namespace Site;

use Core\HttpRequest;

/**
 * This class helps to protect forms from CSRF vulnarability
 *
 * @author kryoz
 */
class FormToken
{
	const FIELDNAME = 'formtoken';
	const TTL = '7200';

    public static function create()
    {
        return new static;
    }

	public function getToken()
	{
		$time = time();
		$token = sha1(mt_rand(0, 1000000));

		$this->setSessionToken($token, $time);

		return "<input name='" . self::FIELDNAME . "' value='{$token}' type='hidden' />";
	}

	public function validateToken(HttpRequest $request, $clear = true)
	{
        if (session_status() !== PHP_SESSION_ACTIVE) {
            return false;
        }

		$valid = false;
		$posted = isset($request->getPost()[self::FIELDNAME]) ? $request->getPost()[self::FIELDNAME] : '';

		if (!empty($posted)) {
			if ($this->getSessionToken($posted)) {
				if ($this->getSessionToken($posted) >= time() - self::TTL) {
					$valid = true;
				}
				if ($clear)
					unset($_SESSION[self::FIELDNAME]);
			}
		}

		return $valid;
	}

    private function getSessionToken($prop)
    {
        return isset($this->getSession()[self::FIELDNAME][$prop]) ? $this->getSession()[self::FIELDNAME][$prop] : false;
    }

    private function setSessionToken($prop, $val)
    {
        $_SESSION[self::FIELDNAME][$prop] = $val;
    }

    private function getSession()
    {
        return $_SESSION;
    }
}

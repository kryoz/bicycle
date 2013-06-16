<?php
namespace Site;

/**
 * This class helps to protect forms from CSRF vulnarability
 *
 * @author kryoz
 */
class FormToken
{
	const FIELDNAME = 'formtoken';
	const TTL = '7200';

    private static $instance;

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

    private function getRequest()
    {
        return $_REQUEST;
    }

    public function __construct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            throw new \Exception('Session has not been initialized');
        }
    }

    public static function create()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

	public function getToken()
	{
		$time = time();
		$token = sha1(mt_rand(0, 1000000));

		$this->setSessionToken($token, $time);

		return "<input name='" . self::FIELDNAME . "' value='{$token}' type='hidden' />";
	}

	public function validateToken($clear = true)
	{
		$valid = false;
		$posted = isset($this->getRequest()[self::FIELDNAME]) ? $this->getRequest()[self::FIELDNAME] : '';

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

}

<?php

namespace Core;

class Session
{

	public function __construct()
	{
		@session_start();

		if (DEBUG && session_id() == '') {
			Debug::log('Failed to start session.');
			if (function_exists('error_get_last')) {
				$error = error_get_last();
				if (isset($error['message']))
					$message = $error['message'];
			}
			Debug::log($message);
		}
	}

	/**
	 * Ends the current session and store session data.
	 */
	public function close()
	{
		if (session_id() !== '')
			@session_write_close();
	}

	/**
	 * Frees all session variables and destroys all data registered to a session.
	 */
	public function destroy()
	{
		if (session_id() !== '') {
			@session_unset();
			@session_destroy();
		}
	}

	public function get($key)
	{
		return isset($_SESSION[$key]) ? $_SESSION[$key] : false;
	}

	public function set($key, $value)
	{
		$session[$key] = $value;
	}

	/**
	 * Removes a session variable.
	 * @param mixed $key the name of the session variable to be removed
	 * @return mixed the removed value, null if no such session variable.
	 */
	public function remove($key)
	{
		if (isset($_SESSION[$key])) {
			$value = $_SESSION[$key];
			unset($_SESSION[$key]);
			return $value;
		}
		else
			return null;
	}

	/**
	 * @param mixed $key session variable name
	 * @param string namespace
	 * @return boolean whether there is the named session variable
	 */
	public function exists($key, $namespace = null)
	{
		return isset($_SESSION[$key]);
	}

	/**
	 * @return array the list of all session variables in array
	 */
	public function toArray()
	{
		return $_SESSION;
	}

	/**
	 * @return boolean whether the session has started
	 */
	public function getIsStarted()
	{
		return session_id() !== '';
	}

	/**
	 * @return string the current session ID
	 */
	public function getSessionID()
	{
		return session_id();
	}

	/**
	 * @param string $value the session ID for the current session
	 */
	public function setSessionID($value)
	{
		session_id($value);
	}

	/**
	 * Updates the current session id with a newly generated one .
	 * Please refer to {@link http://php.net/session_regenerate_id} for more details.
	 * @param boolean $deleteOldSession Whether to delete the old associated session file or not.
	 * @since 1.1.8
	 */
	public function regenerateID($deleteOldSession = false)
	{
		session_regenerate_id($deleteOldSession);
	}

	/**
	 * @return string the current session name
	 */
	public function getSessionName()
	{
		return session_name();
	}

	/**
	 * @param string $value the session name for the current session, must be an alphanumeric string, defaults to PHPSESSID
	 */
	public function setSessionName($value)
	{
		session_name($value);
	}

	/**
	 * @return string the current session save path, defaults to '/tmp'.
	 */
	public function getSavePath()
	{
		return session_save_path();
	}

	/**
	 * @param string $value the current session save path
	 * @throws CException if the path is not a valid directory
	 */
	public function setSavePath($value)
	{
		if (is_dir($value))
			session_save_path($value);
		else
			throw new Exception("Session::savePath {$value} is not a valid directory.");
	}

	/**
	 * @return array the session cookie parameters.
	 * @see http://us2.php.net/manual/en/function.session-get-cookie-params.php
	 */
	public function getCookieParams()
	{
		return session_get_cookie_params();
	}

	/**
	 * Sets the session cookie parameters.
	 * The effect of this method only lasts for the duration of the script.
	 * Call this method before the session starts.
	 * @param array $value cookie parameters, valid keys include: lifetime, path, domain, secure.
	 * @see http://us2.php.net/manual/en/function.session-set-cookie-params.php
	 */
	public function setCookieParams($value)
	{
		$data = session_get_cookie_params();
		extract($data);
		extract($value);
		if (isset($httponly))
			session_set_cookie_params($lifetime, $path, $domain, $secure, $httponly);
		else
			session_set_cookie_params($lifetime, $path, $domain, $secure);
	}

	/**
	 * @return string how to use cookie to store session ID. Defaults to 'Allow'.
	 */
	public function getCookieMode()
	{
		if (ini_get('session.use_cookies') === '0')
			return 'none';
		else if (ini_get('session.use_only_cookies') === '0')
			return 'allow';
		else
			return 'only';
	}

	/**
	 * @param string $value how to use cookie to store session ID. Valid values include 'none', 'allow' and 'only'.
	 */
	public function setCookieMode($value)
	{
		if ($value === 'none') {
			ini_set('session.use_cookies', '0');
			ini_set('session.use_only_cookies', '0');
		} else if ($value === 'allow') {
			ini_set('session.use_cookies', '1');
			ini_set('session.use_only_cookies', '0');
		} else if ($value === 'only') {
			ini_set('session.use_cookies', '1');
			ini_set('session.use_only_cookies', '1');
		}
		else
			throw new Exception('Session::cookieMode can only be "none", "allow" or "only".');
	}

	/**
	 * @return boolean whether transparent sid support is enabled or not, defaults to false.
	 */
	public function getUseTransparentSessionID()
	{
		return ini_get('session.use_trans_sid') == 1;
	}

	/**
	 * @param boolean $value whether transparent sid support is enabled or not.
	 */
	public function setUseTransparentSessionID($value)
	{
		ini_set('session.use_trans_sid', $value ? '1' : '0');
	}

	/**
	 * @return integer the number of seconds after which data will be seen as 'garbage' and cleaned up, defaults to 1440 seconds.
	 */
	public function getTimeout()
	{
		return (int) ini_get('session.gc_maxlifetime');
	}

	/**
	 * @param integer $value the number of seconds after which data will be seen as 'garbage' and cleaned up
	 */
	public function setTimeout($value)
	{
		ini_set('session.gc_maxlifetime', $value);
	}

}


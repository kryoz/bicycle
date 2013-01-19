<?php

namespace Core;

class Router
{

	const CONTROLLER = 'c';
	const PAGE = 'p';
	const CCLASS = 'C';

	private static $path;
	private static $controller;
	private static $page;
	private static $args;
	private static $params;

	function __construct()
	{
		$this->setPath(COMPONENTS);
	}

	/**
	 * Sets component path
	 * @param string $path
	 * @throws Exception
	 */
	private function setPath($path)
	{
		self::$path = $path;

		if (is_dir($path) == false)
			throw new Exception(__CLASS__ . '::' . __FUNCTION__ . ': Invalid controller path `' . $path . '`');

		self::$path = $path;
	}

	/**
	 * Method to parse URL string
	 * Returns controller name, path and parameters if has any
	 */
	private function getController()
	{
		$route = (empty($_SERVER["REQUEST_URI"])) ? '' : $_SERVER["REQUEST_URI"];

		// Cutting root url from url string
		if ($route)
			$route = substr($route, strlen(URLROOT));

		// Avoiding duplicates
		$mainpage = [
			INDEX . '.php',
			INDEX,
			INDEX . '/',
			INDEX . VIRT_EXT
		];

		if (in_array($route, $mainpage))
			self::redirect();

		if (empty($route))
			$route = INDEX;
		else
			$route = trim($route, '/\\');

		// Getting part from url after '?' and transforming it to array
		$params = explode('?', $route);
		if (is_array($params)) {
			$query = explode('&', $params[1]);
			if (is_array($query)) {
				foreach ($query as $i => $part) {
					$pair = explode('=', $part);
					unset($query[$i]);

					$query[$pair[0]] = $pair[1];
				}
			}
			if (SEFENABLED) {
				$route = $params[0];
			} else {
				$route = $query;
			}
		}

		if (SEFENABLED) {
			//Filtering "-" by transforming it to "_"
			$route = preg_replace('#(\-)#', '_', $route);

			//Cutting virtual file extension
			$pattern = '#(\\' . VIRT_EXT . ')$#';
			$route = preg_replace($pattern, '', $route);

			/* Main router logic */
			$parts = explode('/', $route);

			if (is_array($parts)) {
				if ($parts[0] == INDEX) {
					$controller = array_shift($parts);
					if (!empty($parts))
						self::redirect(implode('/', $parts));
				}

				$controller = array_shift($parts);
				$args = $parts;
			}
			else
				$controller = $route;


			self::$controller = $controller;
			self::$args = is_array($args) ? $args : [$args];
			self::$page = isset(self::$args[0]) ? array_shift(self::$args) : INDEX;
			self::$params = $params;

			// Case of hidden controller and assuming INDEX
			$controller_file = $this->getControllerPath();
			if (!is_readable($controller_file)) {
				$controller_file = self::$path . INDEX . DS . INDEX . '.php';
				// assume "controller" is a page
				if (self::$controller) {
					self::$page = self::$controller;
				}
				self::$controller = INDEX;
			}
		} else {
			if (isset($route[self::CONTROLLER])) {
				self::$controller = $route[self::CONTROLLER];
				unset($route[self::CONTROLLER]);
			} else {
				self::$controller = INDEX;
			}

			if (isset($route[self::PAGE])) {
				self::$page = $route[self::PAGE];
				unset($route[self::PAGE]);
			} else {
				self::$page = INDEX;
			}

			self::$args = $route;
			self::$params = $params[1];
		}
	}

	private static function getControllerPath()
	{
		return self::$path . self::$controller . DS . self::$controller . '.php';
	}

	/*
	 * Method to find controller and delegate the control to it
	 */

	public function delegate()
	{
		try {
			$this->getController();

			$controller_file = $this->getControllerPath();

			require_once $controller_file;

			// Delegating control
			$class = 'Components\\' . self::CCLASS . self::$controller;
			$controller = new $class(self::$path . self::$controller . DS);

			if (is_callable([$class, self::$page])) {
				$controller->{self::$page}(self::$args, self::$params);
			}
			//this case is required for complex controllers
			elseif (isset($controller->complex)) {
				$controller->index(self::$args, self::$params);
			} else {
				self::NoPage();
			}
		} catch (Exception $e) {
			Debug::log(__CLASS__ . '::' . __FUNCTION__ . ': ' . $this->getControllerPath(), $e->getMessage());
		}
	}

	public static function redirect($url = '', $raw = false)
	{
		$address = $raw ? $url : PROTOCOL . '://' . $_SERVER['HTTP_HOST'] . URLROOT . $url;
		header("HTTP/1.1 301 Moved Permanently");
		header('Location: ' . $address);
		exit();
	}

	public static function NoPage()
	{
		header("HTTP/1.1 404 Not Found");

		self::$controller = 'error404';

		require_once self::getControllerPath();

		$class = self::CCLASS . self::$controller;

		$controller = new $class(self::$controller);
		$controller->index(self::$args, self::$params);
		exit();
	}

}

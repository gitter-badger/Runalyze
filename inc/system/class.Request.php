<?php
/**
 * This file contains class::Request
 * @package Runalyze\System
 */
/**
 * Request
 * @author Hannes Christiansen
 * @package Runalyze\System
 */
class Request {
	/**
	 * Get requested URI
	 * @return string
	 */
	static public function Uri() {
		return $_SERVER['REQUEST_URI'];
	}

	/**
	 * Get requested script name
	 * @return string
	 */
	static public function ScriptName() {
		return $_SERVER['SCRIPT_NAME'];
	}

	/**
	 * Get requested filename
	 * @return string
	 */
	static public function Basename() {
		return basename(self::Uri());
	}

	/**
	 * Get current folder of request
	 * @return string
	 */
	static public function CurrentFolder() {
		return basename(dirname(self::Uri()));
	}

	/**
	 * Is the user on a shared page?
	 * @return boolean
	 */
	static public function isOnSharedPage() {
		return SharedLinker::isOnSharedPage();
	}

	/**
	 * Was the request an AJAX-request?
	 * Be careful: Does not work if a file is sent via jQuery!
	 * @return boolean
	 */
	static public function isAjax() {
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
	}

	/**
	 * Is request HTTPS?
	 * @return boolean
	 */
	static public function isHttps() {
		return isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off';
	}

	/**
	 * Get protocol (http/https)
	 * @return string
	 */
	static public function getProtocol() {
		if (self::isHttps())
			return 'https';

		return 'http';
	}

	/**
	 * Get ID send as post or get
	 * @return mixed
	 */
	static public function sendId() {
		if (isset($_GET['id']))
			return $_GET['id'];
		if (isset($_POST['id']))
			return $_POST['id'];

		return false;
	}

	/**
	 * Get parameter send via GET or POST
	 * @param string $key
	 * @return string 
	 */
	static public function param($key) {
		if (isset($_GET[$key]))
			return $_GET[$key];
		if (isset($_POST[$key]))
			return $_POST[$key];

		return '';
	}
}

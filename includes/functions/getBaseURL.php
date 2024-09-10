<?php

function getBaseURL(){
	if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
		$protocol = 'https://';
	}else {
		$protocol = 'http://';
	}

	return $protocol . $_SERVER['HTTP_HOST'] . rtrim($GLOBALS['config']->base_url, '/') . '/';
}
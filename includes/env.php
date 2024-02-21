<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

define('APP_ROOT', realpath(dirname(dirname(__FILE__))));

$config = null;
$pdo = null;

$config_file = APP_ROOT."/config/config.json";
$db_file = APP_ROOT.'/database/fuckit.db';

if(file_exists($config_file)){
	$config = json_decode(file_get_contents($config_file));
}

if(file_exists($db_file)){
	$pdo = new PDO('sqlite:'.$db_file);
}

spl_autoload_register(function ($class) {
	$class_paths = [
		'/includes/classes/',
		'/includes/classes/controllers/',
		'/includes/classes/models/'
	];
	foreach($class_paths as $path){
		$class_file = APP_ROOT.$path.$class.".php";
		if(file_exists($class_file)){
			require $class_file;
			return;
		}
	}
});
<?php

define('APP_ROOT', realpath(dirname(dirname(__FILE__))));

$config = null;
$pdo = null;

$server_config_file = APP_ROOT."/config/server.json";
$app_config_file = APP_ROOT."/config/app.json";
$db_file = APP_ROOT.'/database/milton.db';

if(file_exists($server_config_file) && file_exists($app_config_file)){
	$server_config_contents = file_get_contents($server_config_file);
	$app_config_contents = file_get_contents($app_config_file);
	$app_config = empty($app_config_contents) ? [] : @json_decode($app_config_contents, true);
	$server_config = empty($server_config_contents) ? [] : @json_decode($server_config_contents, true);
	if(!empty($app_config) && !empty($server_config)){
		$config = (object) @array_merge($server_config, $app_config);
	}
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
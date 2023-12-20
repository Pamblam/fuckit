<?php

define('APP_ROOT', realpath(dirname(dirname(__FILE__))));

$pdo = new PDO('sqlite:'.APP_ROOT.'/database/fuckit.db');

spl_autoload_register(function ($class) {
	$class_file = APP_ROOT."/includes/classes/$class.php";
	if(file_exists($class_file)){
		require $class_file;
	}
});
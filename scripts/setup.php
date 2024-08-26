<?php

/**
 * Create the database file and the table structure
 */

clearstatcache();
require realpath(dirname(dirname(__FILE__)))."/includes/env.php";
require APP_ROOT."/includes/functions/file_upload_max_size.php";
require APP_ROOT."/includes/functions/checkAppFilePerms.php";

echo "\n\nInstalling Fuckit\n";
echo "=================\n";

echo "Checking file permissions...\n";
$missing_perms = checkAppFilePerms();
if(!empty($missing_perms)){
	echo "!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!\n";
	echo "!! Unable to access required system files. Please run the following: !!\n";
	foreach($missing_perms as $err) echo $err['solution']."\n";
	echo "!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!\n";
	exit(1);
}

echo "Permissions OK!\n";

echo "Setting up database\n";

$rebuilding_database = true;
$rebuilding_config = true;
$config_default_base_url = '/';
$config_defualt_max_file_size = 8000000;

$db_directory = realpath(dirname($db_file));

// Opening the file in 'w' mode truncates it, 
// resetting the exiting database, if there is one
if(file_exists($db_file)){
	echo "Database file exists.\n";
	$rebuild_resp = promptUser("Do you want to reinstall the database? (y/n):");
	while($rebuild_resp !== 'y' && $rebuild_resp !== 'n'){
		echo "Invalid response\n";
		$rebuild_resp = promptUser("Do you want to reinstall the database? (y/n):");
	}
	if($rebuild_resp === 'n') $rebuilding_database = false;

	if($rebuilding_database){
		echo "Truncating database.\n";
		$fp = fopen($db_file, "w");
		if(false === $fp){
			echo "Can't open DB file. Ensure you has correct permissions and ownership.\n";
			exit(1);
		}
		fclose($fp);
	}
}else{
	echo "Database file does not exist. Creating it.\n";
	try{
		$pdo = new PDO('sqlite:'.$db_file);
		if(empty($pdo)) throw Exception("Couldn't create database file.");
	}catch(Exception $e){
		echo "Error: ".$e->getMessage()."\n";
		echo "Can't create the database file. Ensure PHP has proper permissions to read it.\n";
		exit(1);
	}
}

if($rebuilding_database){
	// Iterate thru all the sql files and run them
	$sql_files_dir = APP_ROOT."/database/sql";
	$sql_files = @scandir($sql_files_dir);
	if(false === $sql_files){
		echo "Can't scan the database directory ($sql_files_dir). Ensure PHP has proper permissions to read it.\n";
		exit(1);
	}
	foreach($sql_files as $file){
		if(substr($file, -4) !== '.sql') continue;
		$sql = @file_get_contents("$sql_files_dir/$file");
		if(false === $sql){
			echo "Can't scan the sql file ($sql_files_dir/$file). Ensure PHP has proper permissions to read it.\n";
			exit(1);
		}
		$sql_statements = explode(";\n", $sql);
		foreach($sql_statements as $sql_statement){
			try{
				$pdo->exec($sql_statement);
			}catch(PDOException $e){
				echo "Error: ".$e->getMessage()."\n";
				echo "Can't import the sql file ($sql_files_dir/$file). Ensure PHP has proper permissions to read it and the database file.\n";
				exit(1);
			}
		}
	}

	createUser();
	$create_another = promptUser("Do you want to create another user? (y/n):");
	while($create_another === 'y'){
		createUser();
		$create_another = promptUser("Do you want to create another user? (y/n):");
	}
}else{
	$create_another = promptUser("Do you want to create a new user? (y/n):");
	while($create_another === 'y'){
		createUser();
		$create_another = promptUser("Do you want to create another user? (y/n):");
	}
}

if(file_exists($config_file)){
	echo "Config file exists.\n";
	$rebuild_resp = promptUser("Do you want to reinstall the config file? (y/n):");
	while($rebuild_resp !== 'y' && $rebuild_resp !== 'n'){
		echo "Invalid response\n";
		$rebuild_resp = promptUser("Do you want to reinstall the config file? (y/n):");
	}
	if($rebuild_resp === 'n') $rebuilding_config = false;

	if($rebuilding_config){

		try{
			$cfg = @file_get_contents($config_file);
			$cfg = @json_decode($cfg);
			if(!empty($cfg) && !empty($cfg->base_url)) $config_default_base_url = $cfg->base_url;
			if(!empty($cfg) && !empty($cfg->max_upload_size)) $config_defualt_max_file_size = $cfg->max_upload_size;
		}catch(Exception $e){}

		echo "Truncating config.\n";
		$fp = fopen($config_file, "w");
		if(false === $fp){
			echo "Can't open config file. Ensure PHP has correct permissions and ownership.\n";
			exit(1);
		}
		fclose($fp);
	}
}

if($rebuilding_config){
	$config_obj = [];

	// Set the base URL
	echo "Setting the relative URL to the `public` directory of the app.\n";
	echo "If the app is hosted in a subdirectory (eg: localhost/fuckit/public/) then the relative URL would be anything after your domain (eg: /fuckit/public/).\n";
	$base_url = promptUser("Enter the app's base URL (default $config_default_base_url):");
	if(empty($base_url)) $base_url = $config_default_base_url;
	$config_obj['base_url'] = $base_url;
	$config_obj['max_upload_size'] = getMaxFileSize($config_defualt_max_file_size);

	$app_title = promptUser("Enter the app's title (default Fuckit):");
	if(empty($app_title)) $app_title = "Title";
	$config_obj['title'] = $app_title;

	$res = @file_put_contents($config_file, json_encode($config_obj, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
	if(false === $res){
		echo "Can't create config file. Ensure PHP has correct permissions and ownership.\n";
		exit(1);
	}
}

clearstatcache();
exit(0);

function getMaxFileSize($config_defualt_max_file_size){

	$ini_max_file_size = file_upload_max_size();
	if($config_defualt_max_file_size > $ini_max_file_size){
		$config_defualt_max_file_size = $ini_max_file_size;
	}

	$max_upload_size = promptUser("Enter the app's max image upload size (default $config_defualt_max_file_size):");
	if(empty($max_upload_size)) $max_upload_size = $config_defualt_max_file_size;
	if(!is_numeric($max_upload_size)){
		echo "Invalid input. Enter the max upload size, in numbers.\n";
		return getMaxFileSize($config_defualt_max_file_size);
	}

	$max_upload_size = intval($max_upload_size);
	if($max_upload_size > $ini_max_file_size){
		echo "Invalid input. That was greater than PHP's max upload size of $ini_max_file_size.\n";
		return getMaxFileSize($config_defualt_max_file_size);
	}
	return $max_upload_size;
}

function getNewUsername(){
	global $pdo;
	$username = promptUser("Enter a username (default: admin):");
	if(empty($username)) $username = 'admin';
	$valid_username = preg_match("/^[a-zA-Z0-9_]+$/", $username);
	if($valid_username !== 1 || strlen($username) < 4 || strlen($username) > 15){
		echo "Invalid username. May contain only letters, numbers, and underscores. Must be at least 4 characters and no more than 15.\n";
		return getNewUsername();
	}

	// check to see if this user exists already
	try{
		$stmt = $pdo->prepare("select count(1) from `users` where username = ?");
		$stmt->execute([$username]);
		$cnt = intval($stmt->fetchColumn());
	}catch(PDOException $e){
		echo "Error: ".$e->getMessage()."\n";
		echo "Check the `users` table. Ensure PHP has proper permissions to read the database file.\n";
		exit(1);
	}
	if($cnt > 0){
		echo "This username is already in use. Pick another one.\n";
		return getNewUsername();
	}
	return $username;
}

function getNewPassword($username){
	$password = promptUser("Enter a password for user $username (default: password):");
	if(empty($password)) $password = 'password';
	if(strlen($password) < 8){
		echo "Invalid password. Must be at least 8 characters.\n";
		return getNewPassword($username);
	}
	return $password;
}

function getNewDisplayName($username){
	$display_name = promptUser("Enter the real name for user $username (default: Administrator):");
	if(empty($display_name)) $display_name = 'Administrator';
	if(strlen($display_name) < 3){
		echo "Invalid name. Must be at least 3 characters.\n";
		return getNewDisplayName($username);
	}
	return $display_name;
}

function createUser(){
	global $pdo;
	$username = getNewUsername();
	$password = getNewPassword($username);
	$display_name = getNewDisplayName($username);
	try{
		$stmt = $pdo->prepare("INSERT INTO `users` (`username`, `password`, `display_name`) VALUES (?, ?, ?);");
		$stmt->execute([$username, md5($password), $display_name]);
	}catch(PDOException $e){
		echo "Error: ".$e->getMessage()."\n";
		echo "Can't create user. Ensure PHP has proper permissions to read the database file.\n";
		exit(1);
	}
	echo "Created user: $username\n";
}

function promptUser($prompt){
	echo "$prompt ";
	$handle = fopen ("php://stdin","r");
	$line = fgets($handle);
	fclose($handle);
	return trim($line); 
}
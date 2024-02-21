<?php

/**
 * Create the database file and the table structure
 */

require realpath(dirname(dirname(__FILE__)))."/includes/env.php";

echo "\n\nInstalling Fuckit\n";
echo "=================\n";
echo "Setting up database\n";

$rebuilding_database = true;
$rebuilding_config = true;

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
			echo "Can't open DB file. Ensure PHP has correct permissions and ownership.\n";
			exit(1);
		}
		fclose($fp);
	}
}else{
	echo "Database file does not exist. Creating it.\n";
	try{
		$pdo = new PDO('sqlite:'.$db_file);
	}catch(Exception $e){
		echo "Error: ".$e->getMessage()."\n";
		echo "Can't create the database file. Ensure PHP has proper permissions to read it.\n";
		exit(1);
	}
}

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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
		echo "Truncating config.\n";
		$fp = fopen($db_file, "w");
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
	$base_url = promptUser("Enter the app's base URL (default /):");
	if(empty($base_url)) $base_url = '/';
	$config_obj['base_url'] = $base_url;

	$max_upload_size = promptUser("Enter the app's max image upload size (default 8000000):");
	if(empty($max_upload_size)) $max_upload_size = '8000000';
	$config_obj['max_upload_size'] = $max_upload_size;

	$res = @file_put_contents($config_file, json_encode($config_obj, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
	if(false === $res){
		echo "Can't create config file. Ensure PHP has correct permissions and ownership.\n";
		exit(1);
	}
}

exit(0);

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
<?php

include "fi_ensure_permissions.php";

function fi_check_file_app_permissions(){
	// Ensure we have read access to the SQL files db
	$sql_path_perms = fi_ensure_permissions(APP_ROOT."/database/sql", ['r','e']);

	// Ensure we have write access to the DB directory
	$db_path_perms = fi_ensure_permissions(APP_ROOT."/database", ['r','w', 'e']);

	// Ensure that we have read and write permission on the db file, if it exists
	$db_file_perms = [];
	if(file_exists(APP_ROOT."/database/fuckit.db")){
		$db_file_perms = fi_ensure_permissions(APP_ROOT."/database/fuckit.db", ['r','w']);
	}

	// Ensure we have write access to the config directory
	$config_path_perms = fi_ensure_permissions(APP_ROOT."/config", ['r','w', 'e']);

	// Ensure that we have read and write permission on the server config file, if it exists
	$server_config_file_perms = [];
	if(file_exists(APP_ROOT."/config/server.json")){
		$server_config_file_perms = fi_ensure_permissions(APP_ROOT."/config/server.json", ['r','w']);
	}

	// Ensure that we have read and write permission on the app config file, if it exists
	$app_config_file_perms = [];
	if(file_exists(APP_ROOT."/config/app.json")){
		$app_config_file_perms = fi_ensure_permissions(APP_ROOT."/config/app.json", ['r','w']);
	}

	// Ensure we have write access to the config directory
	$src_path_perms = fi_ensure_permissions(APP_ROOT."/src", ['e', 'r']);

	// Themes
	$theme_path_perms = fi_ensure_permissions(APP_ROOT."/src/themes", ['e', 'r']);

	return array_merge(
		$sql_path_perms, 
		$db_path_perms, 
		$db_file_perms, 
		$config_path_perms, 
		$server_config_file_perms, 
		$app_config_file_perms,
		$src_path_perms, 
		$theme_path_perms
	);
}
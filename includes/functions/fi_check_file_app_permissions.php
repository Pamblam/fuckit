<?php

function fi_check_file_app_permissions(){
	// Ensure we have read access to the SQL files db
	$sql_path_perms = fi_ensure_permissions(APP_ROOT."/database/sql", ['r','e']);

	// Ensure we have write access to the DB directory
	$db_path_perms = fi_ensure_permissions(APP_ROOT."/database", ['r','w', 'e']);

	// Ensure that we have read and write permission on the db file, if it exists
	$db_file_perms = [];
	if(file_exists(APP_ROOT."/database/milton.db")){
		$db_file_perms = fi_ensure_permissions(APP_ROOT."/database/milton.db", ['r','w']);
	}

	// Ensure we have write access to the package.json directory
	$package_file_perms = fi_ensure_permissions(APP_ROOT."/package.json", ['r','w', 'e']);
	$packagelock_file_perms = fi_ensure_permissions(APP_ROOT."/package-lock.json", ['r','w', 'e']);
	$nm_packagelock_file_perms = fi_ensure_permissions(APP_ROOT."/node_modules/.package-lock.json", ['r','w', 'e']);

	// Ensure we have write access to the assets directory
	$public_path_perms = fi_ensure_permissions(APP_ROOT."/public", ['r','w', 'e']);
	$asset_path_perms = fi_ensure_permissions(APP_ROOT."/public/assets", ['r','w', 'e']);
	$js_path_perms = fi_ensure_permissions(APP_ROOT."/public/assets/js", ['r','w', 'e']);
	$main_path_perms = fi_ensure_permissions(APP_ROOT."/public/assets/js/main.js", ['r','w']);
	$main_map_path_perms = fi_ensure_permissions(APP_ROOT."/public/assets/js/main.js.map", ['r','w']);

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
		$theme_path_perms,
		$package_file_perms,
		$public_path_perms,
		$asset_path_perms,
		$js_path_perms,
		$main_path_perms,
		$main_map_path_perms,
		$packagelock_file_perms,
		$nm_packagelock_file_perms
	);
}
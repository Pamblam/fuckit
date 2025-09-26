<?php

require realpath(dirname(dirname(__FILE__)))."/includes/env.php";

$missing_perms = fi_check_file_app_permissions();
$missing_tables = fi_check_tables();
$missing_user = fi_check_missing_user();
$missing_deps = fi_check_missing_deps();
$missing_node_modules = fi_check_missing_node_modules();

$installed = !empty($config) 
	&& !empty($pdo)
	&& empty($missing_perms) 
	&& empty($missing_tables)
	&& empty($missing_deps)
	&& !$missing_node_modules
	&& !$missing_user;

if($installed){
	if(fi_is_404()) http_response_code(404);
	require fi_resolve_theme_file('index.php');
}else{
	require fi_resolve_theme_file('installer/installer.php');
}
	
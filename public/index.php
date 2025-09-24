<?php

require realpath(dirname(dirname(__FILE__)))."/includes/env.php";
require APP_ROOT."/includes/functions/fi_check_file_app_permissions.php";
require APP_ROOT."/includes/functions/fi_get_base_url.php";
require APP_ROOT."/includes/functions/fi_is_404.php";
require APP_ROOT."/includes/functions/fi_resolve_theme_file.php";
require APP_ROOT."/includes/functions/fi_check_tables.php";
require APP_ROOT."/includes/functions/fi_check_missing_user.php";
require APP_ROOT."/includes/functions/fi_browser_installer.php";

$missing_perms = fi_check_file_app_permissions();
$missing_tables = fi_check_tables();
$missing_user = fi_check_missing_user();

$installed = !empty($config) 
	&& !empty($pdo)
	&& empty($missing_perms) 
	&& empty($missing_tables)
	&& !$missing_user;

if($installed){
	if(fi_is_404()) http_response_code(404);
	require fi_resolve_theme_file('index.php');
}else{
	require fi_resolve_theme_file('installer/installer.php');
}
	
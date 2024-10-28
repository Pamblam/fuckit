<?php
/**
 * Set the theme
 */

clearstatcache();
require realpath(dirname(dirname(__FILE__)))."/includes/env.php";
require APP_ROOT."/includes/functions/fi_check_file_app_permissions.php";

if(empty($argv[1])){
	echo "No theme indicated.\n";
	exit(1);
}

$theme_name = $argv[1];

echo "\n\nInstalling Theme\n";
echo "=================\n";

echo "Checking file permissions...\n";
$missing_perms = fi_check_file_app_permissions();
if(!empty($missing_perms)){
	echo "!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!\n";
	echo "!! Unable to access required system files. Please run the following: !!\n";
	foreach($missing_perms as $err) echo $err['solution']."\n";
	echo "!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!\n";
	exit(1);
}

if(empty($config)){
	echo "Install Fuckit before installing a theme. Run:\nnpm run setup\n";
	exit(1);
}

echo "Permissions OK!\n";

if(!is_dir(APP_ROOT."/src/themes/$theme_name")){
	echo "Theme not found in /src/themes directory.\n";
	exit(1);
}

$app_config = json_decode(file_get_contents($app_config_file));
$app_config->theme = $theme_name;
file_put_contents($app_config_file, json_encode($app_config, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
echo "Theme installed!\n";
echo "Run `npm run build` to switch to newly installed theme.\n";

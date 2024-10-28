<?php

/**
 * Create the database file and the table structure
 */

require realpath(dirname(dirname(__FILE__)))."/includes/env.php";

echo "\n\nMapping theme imports\n";
echo "=================\n";

if(empty($config)){
	echo "Config not found. Run `npm run setup`.\n";
	exit;
}

$src_dir = APP_ROOT."/src/";
$core_dir = $src_dir."core/";

$imports = [
	'#config/server'=>'./config/server.json', 
	'#config/app'=>'./config/app.json'
];

$core_files = recursiveScandir($core_dir);
for($i=0; $i<count((array) $core_files); $i++){
	$core_files[$i] = substr($core_files[$i], strlen($core_dir)+1);
	$import_path = "./src/core/".$core_files[$i];
	$path_parts = explode(".", $core_files[$i]);
	array_pop($path_parts);
	$core_files[$i] = implode(".", $path_parts);
	$import_alias = "#".$core_files[$i];
	$imports[$import_alias] = $import_path;
}

if(!empty($config->theme)){
	$theme_dir = $src_dir."themes/{$config->theme}/";
	if(!is_dir($theme_dir)){
		echo "Theme file is missing!\n";
		exit(1);
	}
	echo "Setting theme to: \"{$config->theme}\"\n\n";
	$theme_files = recursiveScandir($theme_dir);

	for($i=0; $i<count((array) $theme_files); $i++){
		$theme_files[$i] = substr($theme_files[$i], strlen($theme_dir)+1);
		$import_path = "./src/themes/{$config->theme}/".$theme_files[$i];
		$path_parts = explode(".", $theme_files[$i]);
		array_pop($path_parts);
		$core_files[$i] = implode(".", $path_parts);
		$theme_alias = "#".$core_files[$i];
		$imports[$theme_alias] = $import_path;
	}
}
echo "Done!\n\n";

$package = json_decode(file_get_contents(APP_ROOT."/package.json"), true);
$package['imports'] = $imports;
file_put_contents(APP_ROOT."/package.json", json_encode($package, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));


function recursiveScandir($dir){
	$files = [];
	$f = scandir($dir);
	foreach($f as $file){
		if(in_array($file, ['.', '..'])) continue;
		$path = "$dir/$file";
		if(is_dir($path)){
			$sub_files = recursiveScandir($path);
			foreach($sub_files as $sub_file) $files[] = $sub_file;
		}else{
			$files[] = $path;
		}
	}
	return $files;
}
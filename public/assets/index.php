<?php

require '../../includes/env.php';

$path = $_SERVER['REQUEST_URI'];
$resolved_path = false;
while(strpos($path, '/assets/') !== false && empty($resolved_path)){
	// get the path as it starts from the first occurance of 'assets'
	$path_parts = explode('/assets/', $path);
	array_shift($path_parts);
	$path = implode('/assets/', $path_parts);

	// check if it exists in the /public directory
	//echo APP_ROOT.'/public/assets/'.$path; exit;
	if(file_exists(APP_ROOT.'/public/assets/'.$path)){
		$resolved_path = APP_ROOT.'/public/assets/'.$path; 
		break;
	}
	
	// Check the theme directory
	$theme_dir = !empty(empty($config)) && !empty($config->theme) ? $config->theme : null;
	if(!empty($theme_dir) && file_exists(APP_ROOT.'/src/'.$theme_dir.'/assets/'.$path)){
		$resolved_path = APP_ROOT.'/src/'.$theme_dir.'/assets/'.$path;
		break;
	}

	// check the core directory
	if(file_exists(APP_ROOT.'/src/core/assets/'.$path)){
		$resolved_path = APP_ROOT.'/src/core/assets/'.$path;
		break;
	}
}

if(empty($resolved_path)){
	http_response_code(404);
	echo "404: not found";
	exit;
}

$content_type = mime_content_type($resolved_path);
if(substr($resolved_path, -4) === '.css') $content_type = 'text/css';
if(substr($resolved_path, -3) === '.js') $content_type = 'text/javascript';

if(empty($content_type)) $content_type = 'application/octet-stream';

header('Content-Type: '.$content_type);
header('Content-Length: ' . filesize($resolved_path));
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// Output the file
readfile($resolved_path);
exit;

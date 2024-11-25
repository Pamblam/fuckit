<?php
require APP_ROOT."/includes/functions/fi_get_js_routes.php";
require APP_ROOT."/includes/functions/fi_path_matches_pattern.php";

/**
 * Check if the requested path is an actual file or a route that is handled on the front-end
 * @return bool
 */
function fi_is_404(){

	// Get the actual requested URL
	if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
		$protocol = 'https://';
	}else {
		$protocol = 'http://';
	}
	$actual_link = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

	// Get the app's base URL
	$base_url = fi_get_base_url();

	// The URL should always start with the app's base URL
	if(strpos($actual_link, $base_url) !== 0){
		return true;
	}

	// The requested path, relative to the app's base URL
	$path = substr($actual_link, strlen($base_url));
	if(empty($path)) return false;

	// Check to see if the requested file is an actual file on the server
	$folders = explode('/', $path);
	$file_found = true;
	$concat_path = '';
	for($i=0; $i<count($folders); $i++){
		if(empty($folders[$i])) continue;
		$concat_path .= '/'.$folders[$i];
		if(!file_exists(APP_ROOT.$concat_path)){
			$file_found = false;
			break;
		}
	}

	// If the file is not a file that exists, check if it is a JS path
	if(!$file_found){
		$matching_js_routes = fi_get_js_routes();
		foreach($matching_js_routes as $pattern){
			if(fi_path_matches_pattern($pattern, '/'.$path)){
				return false;
			}
		}
	}

	return true;
}
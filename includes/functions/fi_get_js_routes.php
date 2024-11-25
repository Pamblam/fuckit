<?php

/**
 * Parse the app's main.js file to get a list of javascript-handled routes
 * @return Array
 */
function fi_get_js_routes(){
	global $config;

	// Get the possible Javascript-created paths
	$js_paths = [];
	$main_jsx_path = APP_ROOT.'/src/core/main.jsx';
	if(!empty($config->theme) && file_exists(APP_ROOT.'/src/themes/'.$config->theme.'/main.jsx')){
		$main_jsx_path = APP_ROOT.'/src/themes/'.$config->theme.'/main.jsx';
	}
	
	$router_raw = file_get_contents($main_jsx_path);
	$last_route_idx = 0;
	while(true){
		preg_match('/<Route\s+/', substr($router_raw, $last_route_idx), $matches, PREG_OFFSET_CAPTURE);
		if(empty($matches)) break;
		$last_route_idx += $matches[0][1]+1;
		preg_match('/path\s*=\s*[\'"]([^\'"]*)[\'"]/', substr($router_raw, $last_route_idx), $matches);
		if(empty($matches)) break;
		$js_paths[] = $matches[1];
	}

	$js_paths = array_filter($js_paths, function($path){
		return $path !== '/' && $path !== '*';
	}); 

	return $js_paths;
}
<?php

/**
 * Get the full path to the given theme file, if it exists 
 * in the configured theme folder, else check for the path 
 * in the src/core directory.
 * @param string $file
 * @throws \Exception
 * @return string
 */
function fi_resolve_theme_file($file){
	$file = ltrim($file, '/');
	if(!empty($GLOBALS['config']) && !empty($GLOBALS['config']->theme)){
		$theme_file = APP_ROOT."/src/".$GLOBALS['config']->theme.'/'.$file;
		if(file_exists($theme_file)) return $theme_file;
	}

	$core_file = APP_ROOT."/src/core/".$file;
	if(file_exists($core_file)) return $core_file;

	throw new Exception("Couldn't resolve theme file: $file.");
}
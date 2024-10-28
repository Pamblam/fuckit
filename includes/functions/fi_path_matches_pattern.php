<?php

/**
 * Given a relative path pattern, such as the ones used in the JSX router, 
 * possibly with variables, as well as an actual relative path, determine 
 * if the path matches the pattern.
 * @param string $pattern
 * @param string $path
 * @return bool
 */
function fi_path_matches_pattern($pattern, $path){
    $pattern_parts = explode('/', $pattern);
    $path_parts = explode('/', $path);
    if(count($path_parts) > count($pattern_parts)) return false;
    for($i = 0; $i<min(count($pattern_parts), count($path_parts)); $i++){
        if(substr($pattern_parts[$i], 0, 1) == ':') continue;
        if($pattern_parts[$i] != $path_parts[$i]) return false;
    }
    return true;
}
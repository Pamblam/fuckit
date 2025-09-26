<?php

function fi_check_missing_deps(){
	$missing = [];
	$node_found = false;
	if(file_exists(APP_ROOT.'/config/server.json')){
		$cfg = false;
		try{
			$cfg = @file_get_contents(APP_ROOT.'/config/server.json');
			$cfg = @json_decode($cfg, true);
		}catch(Exception $e){ $cfg=false; }
		if(!empty($cfg) && !empty($cfg['node_path'])){
			$res = fi_run_cmd("$cfg[node_path] -v");
			if($res->exit_status === 0){
				$node_found = true;
			}
		}
	}
	if(!$node_found) $missing[] = 'node';
	return $missing;
}
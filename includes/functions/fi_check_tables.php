<?php
function fi_check_tables(){
	$missing_tables = [];

	if(empty($GLOBALS['pdo'])) return [];

	// Iterate thru all the sql files and run them
	$sql_files_dir = APP_ROOT."/database/sql";
	$sql_files = @scandir($sql_files_dir);
	if(false === $sql_files) return [];

	$stmt = $GLOBALS['pdo']->query("SELECT name FROM sqlite_master WHERE type='table'");
	$tables = []; while($row = $stmt->fetch(PDO::FETCH_ASSOC)) $tables[] = $row['name'];

	foreach($sql_files as $file){
		if(substr($file, -4) !== '.sql') continue;
		$tablename = substr(basename($file), 0, -4);
		if(!in_array($tablename, $tables)) $missing_tables[] = $tablename;
	}

	return $missing_tables;
}
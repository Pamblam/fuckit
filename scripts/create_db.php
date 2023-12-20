<?php

/**
 * Create the database file and the table structure
 */

require realpath(dirname(dirname(__FILE__)))."/includes/env.php";

$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

$sql_files_dir = APP_ROOT."/database/sql";
$sql_files = scandir($sql_files_dir);
foreach($sql_files as $file){
	if(substr($file, -4) !== '.sql') continue;
	$sql = file_get_contents("$sql_files_dir/$file");
	$sql_statements = explode(";\n", $sql);
	foreach($sql_statements as $sql_statement){
		$pdo->exec($sql_statement);
	}
}



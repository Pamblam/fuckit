<?php

function fi_check_missing_user(){
	if(empty($GLOBALS['pdo'])) return true;
	$users = 0;
	try{
		$stmt = $GLOBALS['pdo']->query("select count(1) from users");
		$users = $stmt === false ? 0 : intval($stmt->fetchColumn());
	}catch(Exception $e){
		$users = 0;
	}
	return $users <= 1;
}
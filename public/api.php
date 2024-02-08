<?php

require "../includes/env.php";

$api = new API($pdo);

foreach($_GET as $method=>$params){
	$api->$method($params);
}

header('Content-Type: application/json');
echo json_encode($api->results, JSON_PRETTY_PRINT);
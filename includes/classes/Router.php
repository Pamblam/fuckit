<?php

class Router{

	public function __construct($pdo) {
		$response = new Response();
		$path_parts = self::parsePath();

		// Path must have at least one, but not more than 3 parts
		if(count($path_parts) > 3 || empty($path_parts)){
			$response->setError("Invalid request", 400)->send();
		}

		// Determine the controller for this request
		$controller_name = array_shift($path_parts);
		$controller_name = $controller_name."Controller";
		$controller_path = realpath(dirname(__FILE__))."/controllers/".$controller_name.".php";
		if(!file_exists($controller_path)){
			$response->setError("Endpoint not found.", 404)->send();
		}

		// Get the id if there is one
		$id = null;
		if(!empty($path_parts)){
			if(is_numeric($path_parts[0])){
				$id = array_shift($path_parts);
			}
		}

		// Get the method name
		$method = null;
		if(empty($path_parts)){
			$method = strtolower($_SERVER['REQUEST_METHOD']);
			if(!in_array($method , ['get', 'post', 'put', 'patch', 'delete'])){
				$response->setError("Method not allowed", 405)->send();
			}
		}else{
			$method = array_shift($path_parts);
		}

		// Fire off the controller
		if(method_exists($controller_name, $method)){
			$controller = new $controller_name($pdo, $response, $id);
			$controller->$method();
			$controller->send();
		}else{
			$response->setError("Method not found.", 404)->send();
		}
		
	}

	private static function parsePath(){
		$path_parts = explode("?", $_SERVER['REQUEST_URI']);
		$path = $path_parts[0];
		$api_path = realpath(dirname(dirname(dirname(__FILE__))))."/public/api";
		$server_path = self::findOverlap($api_path, $path);
		$request_path = substr($path, strlen($server_path));
		$parts = explode('/', $request_path);
		array_shift($parts);
		if($parts[count($parts)-1] === '') array_pop($parts);
		return $parts;
	}

	private static function findOverlap($str1, $str2){
		$return = array();
		$sl1 = strlen($str1);
		$sl2 = strlen($str2);
		$max = $sl1>$sl2?$sl2:$sl1;
		$i=1;
		while($i<=$max){
			$s1 = substr($str1, -$i);
			$s2 = substr($str2, 0, $i);
			if($s1 == $s2){
				$return[] = $s1;
			}
			$i++;
		}
		if(!empty($return)){
			return array_pop($return);
		}
		return false;
	}
}
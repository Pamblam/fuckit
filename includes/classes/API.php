<?php

class API{
	private $pdo;
	private $required_params;
	public $results;
	
	public function __construct($pdo){
		$this->pdo = $pdo;
		$this->results = [];
		$this->required_params = [
			'login' => ['email', 'password']
		];
	}
	
	public function __call($method, $arguments){
		$this->results[$method] = [];
		$this->results[$method]['errors'] = [];
		if(!empty($this->required_params[$method])){
			foreach($this->required_params[$method] as $rqd){
				if(!isset($arguments[0][$rqd])){
					$this->results[$method]['errors'][] = "Required parameter missing: $rqd";
				}
			}
		}
		if(!method_exists($this, $method)){
			$this->results[$method]['errors'][] = "Method $method does not exist.";
		}else if(empty($this->results[$method]['errors'])){
			$this->$method($arguments[0]);
		}
	}
	
	private function login($params){
		$user = User::fromLogin($this->pdo, strtolower(trim($params['email'])), $params['password']);
		if(empty($user)){
			$this->results['login']['errors'][] = "Invalid email or login.";
			return;
		}
		$this->results['login']['data'] = $_SESSION;
	}
}

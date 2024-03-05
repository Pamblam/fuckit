<?php

class SessionController extends ModelController{

	public function __construct($pdo, $response, $id=null) {
		parent::__construct($pdo, $response, $id);
	}

	public function get(){
		$user = $this->getUser();
		if(false == $user){
			$this->response->setData([
				'LoggedIn' => false
			]);
			$this->response->setError("Not logged in", 401)->send();
		}else{
			$this->response->setData([
				'LoggedIn' => true,
				'User' => [
					"id" => $user->get('id'),
					"username" => $user->get('username'),
					"display_name" => $user->get('display_name')
				]
			]);
		}
	}

	public function post(){
		$user = $this->getUser();
		if($user === false){
			if(empty($_POST['username'])) $this->response->setError("Missing parameter: username", 400)->send();
			if(empty($_POST['password'])) $this->response->setError("Missing parameter: password", 400)->send();
			$user = User::fromColumn($this->pdo, 'username', $_POST['username']);
			if(!$user || $user->get('password') !== md5($_POST['password'])) $this->response->setError("Invalid login", 400)->send();
			// do something to create a new session here...
			$new_token = Session::generateToken();
			$this->model_instance->set('ip', Session::getIP());
			$this->model_instance->set('id', $new_token);
			$this->model_instance->set('start_time', time());
			$this->model_instance->set('user_id', $user->get('id'));
			$ua = empty($_SERVER['HTTP_USER_AGENT']) ? '' : $_SERVER['HTTP_USER_AGENT'];
			$this->model_instance->set('user_agent', $ua);
			$this->model_instance->save();
			$this->response->setHeader("x-auth-token: $new_token");
			$this->response->setData([
				'LoggedIn' => false,
				'User' => [
					"id" => $user->get('id'),
					"username" => $user->get('username'),
					"display_name" => $user->get('display_name')
				]
			]);
		}else{
			$this->response->setData([
				'LoggedIn' => true,
				'User' => [
					"id" => $user->get('id'),
					"username" => $user->get('username'),
					"display_name" => $user->get('display_name')
				]
			]);
		}
		
	}

	public function patch(){
		
	}

	public function delete(){
		$user = $this->getUser();
		if(false == $user){
			$this->response->setData([
				'LoggedIn' => false
			]);
			$this->response->setError("Not logged in", 401)->send();
		}else{
			$success = $this->getSession()->delete();
			$this->response->setData([
				'LoggedIn' => true
			]);
			if(false === $success){
				$this->response->setError("Could not log out", 401)->send();
			}
		}
	}

}
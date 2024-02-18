<?php

class SessionController extends Controller{

	public function __construct($pdo, $response, $id=null) {
		parent::__construct($pdo, $response, $id);
	}

	public function get(){
		if(!$this->isAuthorized()){
			$this->response->setError("Not logged in", 401)->send();
		}
	}

	public function post(){
		if(empty($_POST['username'])) $this->response->setError("Missing parameter: username", 400)->send();
		if(empty($_POST['password'])) $this->response->setError("Missing parameter: password", 400)->send();
		$user = User::fromColumn('username', $_POST['username']);
		if($user->get('password') !== md5($_POST['password']))  $this->response->setError("Invalid login", 400)->send();
		// do something to create a new session here...
		$this->model_instance->set('ip', Session::getIP());
		$this->model_instance->set('id', Session::generateToken());
		$this->model_instance->set('start_time', time());
		$this->model_instance->set('user_id', $user->get('id'));
		$ua = empty($_SERVER['HTTP_USER_AGENT']) ? '' : $_SERVER['HTTP_USER_AGENT'];
		$this->model_instance->set('user_agent', $ua);
		$this->model_instance->save();
	}

	public function patch(){
		
	}

	public function delete(){
		
	}

}
<?php

class ModelController extends Controller{

	protected $id;
	protected $model_name;
	protected $model_instance;


	public function __construct($pdo, $response, $id=null){
		parent::__construct($pdo, $response);
		$this->id = $id;

		$className = get_class($this);
		$model_name = substr($className, 0, -10);
		$model_instance = null;
		
		if(!empty($id)){
			$model_instance = call_user_func($model_name."::fromID", $pdo, $id);
		}else{
			$model_instance = new $model_name($pdo);
		}

		if(!empty($model_instance)){
			$this->model_name = $model_name;
			$this->model_instance = $model_instance;
		}

	}

	public function getUser(){
		return $this->user;
	}

	public function getSession(){
		return $this->session;
	}

	private function checkAuthorization(){
		$token = null;
		if(!empty($_SERVER['HTTP_AUTHORIZATION'])) $token = $_SERVER['HTTP_AUTHORIZATION'];
		else if(!empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) $token = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];

		// If there isn't a token in the header, not authorized
		if(empty($token)) return false;

		// If the token doesn't match an active session, not authorized
		$session = Session::fromID($this->pdo, $token);
		if(false === $session) return false;

		// If the IP doesn't match or is hidden, not authorized
		$ip = Session::getIP();
		if(empty($ip) || $ip !== $session->get('ip')) return false;

		// If the session is older than 6 hours, not authorized
		if(intval($session->get('start_time')) < time() - (60*60*6)) return false;

		// If the user agent doesn't match, not authorized, low priority so allow empty string
		$ua = empty($_SERVER['HTTP_USER_AGENT']) ? '' : $_SERVER['HTTP_USER_AGENT'];
		if($ua !== $session->get('user_agent')) return false;

		// Get the user
		$this->user = User::fromID($this->pdo, $session->get('user_id'));
		$this->session = $session;
	}

	public function get(){
		$this->response->setError("Method not allowed", 405)->send();
	}

	public function post(){
		$this->response->setError("Method not allowed", 405)->send();
	}

	public function put(){
		$this->response->setError("Method not allowed", 405)->send();
	}

	public function patch(){
		$this->response->setError("Method not allowed", 405)->send();
	}

	public function delete(){
		$this->response->setError("Method not allowed", 405)->send();
	}

	public function send(){
		$this->response->send();
	}
}

<?php

class Controller{

	protected $pdo;
	protected $response;
	protected $id;
	protected $model_name;
	protected $model_instance;

	public function __construct($pdo, $response, $id=null){
		$this->pdo = $pdo;
		$this->response = $response;
		$this->id = $id;

		$className = get_class($this);
		$this->model_name = substr($className, 0, -10);

		if($id !== null){
			$this->model_instance = call_user_func($this->model_name."::fromID", $pdo, $id);
		}else{
			$this->model_instance = new $this->model_name($pdo);
		}
	}

	public function get(){
		$this->response->setError("Method not allowed", 405);
	}

	public function post(){
		$this->response->setError("Method not allowed", 405);
	}

	public function put(){
		$this->response->setError("Method not allowed", 405);
	}

	public function patch(){
		$this->response->setError("Method not allowed", 405);
	}

	public function delete(){
		$this->response->setError("Method not allowed", 405);
	}

	public function send(){
		$this->response->setData($this->model_instance->getColumns())->send();
	}
}

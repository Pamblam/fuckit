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
	
}

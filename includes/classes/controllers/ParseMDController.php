<?php

class ParseMDController extends Controller{

	public function __construct($pdo, $response, $id=null) {
		parent::__construct($pdo, $response, $id);
	}

	public function get(){
		if(empty($_GET['md'])){
			$this->response->setError("Invalid login", 400)->send();
		}
		$Parsedown = new Parsedown();
		$this->response->setData([
			'html' => $Parsedown->text($_GET['md'])
		]);
	}

}
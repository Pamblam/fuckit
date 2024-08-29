<?php
require APP_ROOT."/includes/functions/mdToHTML.php";

class ParseMDController extends Controller{

	public function __construct($pdo, $response) {
		parent::__construct($pdo, $response);
	}

	public function get(){
		if(empty($_GET['md'])){
			$this->response->setError("Invalid login", 400)->send();
		}

		$this->response->setData([
			'html' => mdToHTML($_GET['md'])
		]);
	}

}
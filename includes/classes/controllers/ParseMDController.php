<?php
require APP_ROOT."/includes/functions/fi_md_to_html.php";

class ParseMDController extends Controller{

	public function __construct($pdo, $response) {
		parent::__construct($pdo, $response);
	}

	public function get(){
		if(empty($_GET['md'])){
			$this->response->setError("Invalid login", 400)->send();
		}

		$this->response->setData([
			'html' => fi_md_to_html($_GET['md'])
		]);
	}

}
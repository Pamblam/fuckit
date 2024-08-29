<?php

class ParseMDController extends Controller{

	public function __construct($pdo, $response) {
		parent::__construct($pdo, $response);
	}

	public function get(){
		if(empty($_GET['md'])){
			$this->response->setError("Invalid login", 400)->send();
		}

		$oringinal_text = $_GET['md'];

		// Images with src that start with `assets/` need the base_url prepended.
		$links_corrected_text = preg_replace('/!\[[^\]]*\]\((assets\/[^\)]+)\)/', '![]('.$GLOBALS['config']->base_url.'$1)', $oringinal_text);

		$Parsedown = new Parsedown();
		$this->response->setData([
			'html' => $Parsedown->text($links_corrected_text)
		]);
	}

}
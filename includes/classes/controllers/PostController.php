<?php

class PostController extends ModelController{

	public function __construct($pdo, $response, $id=null) {
		parent::__construct($pdo, $response, $id);
	}

	public function generateSlug(){
		if(empty($_GET) || empty($_GET["title"])){
			$this->response->setError("No title provided", 400)->send();
		}
		$slug = Post::generateSlug($_GET["title"]);
		$this->response->setData(['slug' => $slug]);
	}

	public function get(){
		
	}

	public function post(){
		
	}

	public function put(){
		
	}

	public function patch(){
		
	}

	public function delete(){
		
	}

}
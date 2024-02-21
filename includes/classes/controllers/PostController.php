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
		$user = $this->getUser();

		// Validate the request
		if(empty($user)){
			$this->response->setError("Not logged in", 401)->send();
		}

		
	}

	public function put(){
		
	}

	public function patch(){
		
	}

	public function delete(){
		
	}

}